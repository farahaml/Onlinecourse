const express = require('express');
const router = express.Router();

//calling handler
const mediaHandler = require('./handler/media');

/*const verifyToken = require('../middlewares/verifyToken');*/

router.post('/', mediaHandler.create);
//calling getAll
router.get('/', mediaHandler.getAll);
//calling destroy
router.delete('/:id', mediaHandler.destroy);

module.exports = router;
