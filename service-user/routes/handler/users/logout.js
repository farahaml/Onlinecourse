const {
    User,
    RefreshToken
} = require('../../../models');

module.exports = async (req, res) => {
    const userId = req.body.user_id;

    //memeriksa apakah userId ada di database
    const user = await User.findByPk(userId);

    //jika user tidak ada
    if (!user) {
        return res.status(404).json({
            stastus: 'error',
            message: 'user not found'
        });
    }

    //jika user ada, maka refresh token akan dihapus sesuai id
    await RefreshToken.destroy({
        where: { user_id: userId }
    });

    //respon sukses
    return res.json({
        stastus: 'success',
        message: 'refresh token deleted'
    });
}