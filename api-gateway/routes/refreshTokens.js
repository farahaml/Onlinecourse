const express = require('express');
const router = express.Router();

//calling handler
const refreshTokensHandler = require('./handler/refresh-tokens');

router.post('/', refreshTokensHandler.refreshToken);


module.exports = router;
