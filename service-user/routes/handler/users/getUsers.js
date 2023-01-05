/*mengambil data list users dari database*/
const { User } = require('../../../models');

module.exports = async (req, res) =>{
//filter user

const userIds = req.query.user_ids || [];

const sqlOptions ={
    attributes: ['id', 'name', 'email', 'role', 'profession', 'avatar']  
}

if (userIds.length) {
    sqlOptions.where = {
        id: userIds
    }
}

const users = await User.findAll(sqlOptions);


/*menampilkan semua user tanpa filter
module.exports = async (req, res) =>{
    const users = await User.findAll({
        attributes: ['id', 'name', 'email', 'role', 'profession', 'avatar']
    });
    */

    return res.json({
        status: 'success',
        data: users
    });
}
