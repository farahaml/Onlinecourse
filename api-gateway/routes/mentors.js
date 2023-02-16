const express = require('express');
const router = express.Router();

//calling handler
const mentorsHandler = require('./handler/mentors');

router.post('/', mentorsHandler.create);
router.put('/:id', mentorsHandler.update);
router.get('/:id', mentorsHandler.get);
router.get('/', mentorsHandler.getAll);
router.delete('/:id', mentorsHandler.destroy);

module.exports = router;
