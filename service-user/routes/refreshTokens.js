/*semua refresh token yang dibuta disimpan di file ini*/

const express = require('express');
const router = express.Router();

const refreshTokensHandler = require('./handler/refresh-tokens');

router.post('/', refreshTokensHandler.create);

module.exports = router;
