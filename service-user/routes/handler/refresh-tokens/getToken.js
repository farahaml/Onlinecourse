const { RefreshToken } = require('../../../models');

module.exports = async (req, res) => {
    const refreshToken = req.query.refresh_token;

    //memeriksa apaakah refreshToken ada di database
    const token = await RefreshToken.findOne({
        where: {
            token: refreshToken
        }
    });

    //jika token tidak ada di database
    if (!token) {
        return res.status(400).json({
            status: 'error',
            message: 'invalid token'
        });
    }

    //jika token ada di database
    return res.json({
        status: 'success',
        token
    });
}