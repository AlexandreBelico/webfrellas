'use strict'
var connection = require('../config');
var md5 = require('md5');
var db = connection.database();
var userRef = db.ref("users");
var nodemailer = require('nodemailer');
var validator = require("email-validator");
var User = require("../model/user");

module.exports.home = async function (req, res) {
    console.log("hello");
};

module.exports.registration = async function (req, res) {
    var email = req.body.email;
    var password = md5(req.body.password);
    var isVerified = false;
    var emailToken = Buffer.from(email + password).toString('base64');

    console.log(emailToken);

    if (typeof email == "undefined" || email == "" || validator.validate(email) == false) {
        res.json({
            message: "Please check email Field.",
            status: false
        });
    } else if (typeof req.body.password == "undefined" || req.body.password == "") {
        res.json({
            message: "Please check password Field.",
            status: false
        });
    } else {
        userRef.orderByChild("email").equalTo(email).on("value", async function (snapshot) {
            try {
                if (snapshot.exists()) {
                    await new Promise(resolve => setTimeout(resolve, 1000));
                    res.json({
                        message: "Email already registered.",
                        status: false
                    });
                } else {
                    var data = {
                        "email": email,
                        "password": password,
                        "isVerified": isVerified,
                        "emailToken": emailToken
                    };

                    // Start store into mongoDb
                    var adduser = new User(data);
                    adduser.save(function (err) {
                        if (err) throw err;
                    });
                    // End store into mongoDb

                    // Start store into firebase
                    userRef.push(data, function (err) {
                        if (err) {
                            res.send(err);
                        } else {
                            var mailTransporter = nodemailer.createTransport({
                                service: 'gmail',
                                auth: {
                                    user: 'ppanchal912@gmail.com',
                                    pass: 'xxvuvelxcgpdqnud'
                                }
                            });

                            var mailDetails = {
                                from: 'ppanchal912@gmail.com',
                                to: email,
                                subject: 'Test mail',
                                html: '<a href="http://13.126.153.185:9595/api/verified?token=' + emailToken + '">Node.js testing mail</a>'
                            };

                            mailTransporter.sendMail(mailDetails, async function (err, data) {
                                if (err) {
                                    await new Promise(resolve => setTimeout(resolve, 1000));
                                    res.json({
                                        message: "Something went wrong.",
                                        status: false
                                    });
                                }
                            });

                            res.json({
                                message: "Student registered successfully.",
                                status: true
                            });
                        }
                    });
                    // End store into firebase
                }
            } catch (error) {
                console.error(error)
            }
        });
    }
};


module.exports.emailVerification = function (req, res) {
    var emailToken = req.query.token;

    User.update({
        _id: found._id,
        'emailToken': emailToken
    }, {
        $set: { 'emailToken': '', 'isVerified': 'true' }
    });

    userRef.orderByChild("emailToken").equalTo(emailToken).on("value", async function (snapshot) {
        if (snapshot.val() == null) {
            await new Promise(resolve => setTimeout(resolve, 1000));
            res.json({
                message: "Something went wrong1.",
                result: false
            });
        } else {
            snapshot.forEach(function (user) {
                var data = {
                    "isVerified": true,
                    "emailToken": ""
                };
                user.ref.child('isVerified').set(true);
                user.ref.child('emailToken').set("");
            });

            res.json({
                message: "Your email has successfully registered.",
                result: true
            });
        }
    });
};

module.exports.login = function (req, res) {

    var email = req.body.email;
    userRef.orderByChild("email").equalTo(email).limitToFirst(1).once("value", function (snapshot) {
        var value = snapshot.val();
        if (value) {
            userRef.orderByChild("email").equalTo(email).limitToFirst(1).once("child_added", function (snapshot) {
                if (snapshot.val().isVerified == false) {
                    res.json({
                        error: 'Please verify your email',
                        status: false
                    });
                } else if (md5(req.body.password) != snapshot.val().password) {
                    res.json({
                        error: 'Please check email and password.',
                        status: false
                    });
                } else {
                    res.json({
                        error: 'User login successfully.',
                        status: true
                    });
                }
            });
        } else {
            res.json({
                error: 'Please check email and password.',
                status: false
            });
        }
    });
};