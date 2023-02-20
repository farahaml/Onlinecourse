const express = require('express');
const router = express.Router();

//call handler
const myCoursesHandler = require('./handler/my-courses');

router.post('/', myCoursesHandler.create);
router.get('/', myCoursesHandler.get);

module.exports = router;