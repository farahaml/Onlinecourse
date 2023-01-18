const express = require('express');
const router = express.Router();

//calling handler
const usersHandler = require('./handler/users');
const verifyToken = require('../middlewares/verifyToken');

router.post('/register', usersHandler.register);
router.post('/login', usersHandler.login);
router.put('/', verifyToken, usersHandler.update);

module.exports = router;
