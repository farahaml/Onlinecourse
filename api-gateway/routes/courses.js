const express = require('express');
const router = express.Router();

//calling handler
const coursesHandler = require('./handler/courses');

const verifyToken = require('../middlewares/verifyToken')

router.post('/', verifyToken, coursesHandler.create);
router.put('/:id', verifyToken, coursesHandler.update);
router.delete('/:id', verifyToken, coursesHandler.destroy);

router.get('/:id', coursesHandler.get);
router.get('/', coursesHandler.getAll);

module.exports = router;
