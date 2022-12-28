/*meload semua handler yang berada di folder users*/

const register = require('./register');
const login = require('./login');

module.exports = {
    register,
    login
}