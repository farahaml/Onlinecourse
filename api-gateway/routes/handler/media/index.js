/*load all file in media folder*/

//memanggil file create
const create = require('./create');
//memanggil file getAll
const getAll = require('./getAll');
//memanggil file destroy
const destroy = require('./destroy');

module.exports = {
    create,
    getAll,
    destroy
};