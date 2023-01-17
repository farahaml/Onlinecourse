const express = require('express');
const router = express.Router();

//calling handler
const usersHandler = require('./handler/users');

router.post('/register', usersHandler.register);
router.post('/login', usersHandler.login);

module.exports = router;
