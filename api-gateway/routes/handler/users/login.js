/*upload data*/

const apiAdapter = require('../../apiAdapter');

//memanggil json web token
const jwt = require('jsonwebtoken');
//memanggil sebuah variabel dari config
const {
    URL_SERVICE_USER,
    JWT_SECRET,
    JWT_SECTRET_REFRESH_TOKEN,
    JWT_ACCES_TOKEN_EXPIRED,
    JWT_REFRESH_TOKEN_EXPIRED
} = process.env;

//varibael untuk memanggil adpter
const api = apiAdapter(URL_SERVICE_USER);

module.exports = async (req, res) => {
    try {
      const user = await api.post('/users/login', req.body);
      const data = user.data.data;
      
      //generate token
      //access token
      const token = jwt.sign({ data }, JWT_SECRET, {expiresIn : JWT_ACCES_TOKEN_EXPIRED});
      //refresh token
      const refreshToken  = jwt.sign( { data }, JWT_SECTRET_REFRESH_TOKEN, {expiresIn : JWT_REFRESH_TOKEN_EXPIRED});

      //menyimpan token 
        await api.post('/refresh_tokens', { refresh_token: refreshToken, user_id: data.id });

        //respon agar token dapat digunakan oleh front-end
        return res.json({
            status: 'success',
            data: {
                token,
                refresh_token: refreshToken
            }
        });
    } catch (error) {

        //when service-media off
        if (error.code === 'ECONNREFUSED') {
            return res.status(500).json({ status: 'error', message: 'service unavailable' });
        }

        const { status, data } = error.response;
        return res.status(status).json(data);
    }
}