/*meload semua handler yang berada di folder users*/

const register = require('./register');
const login = require('./login');
const update = require('./update');

module.exports = {
    register,
    login,
    update
}