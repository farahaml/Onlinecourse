const express = require('express');
const router = express.Router();

//calling handler
const usersHandler = require('./handler/users');

router.post('/register', usersHandler.register);

module.exports = router;
