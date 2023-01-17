/*upload data*/

const apiAdapter = require('../../apiAdapter');

//memanggil sebuah variabel dari config
const {
    URL_SERVICE_USER
} = process.env;

//varibael untuk memanggil adapter
const api = apiAdapter(URL_SERVICE_USER);

module.exports = async (req, res) => {
    try {
      const user = await api.post('/users/register', req.body);
      //reponse if success
      return res.json(user.data);  
    } catch (error) {

        //when service-media off
        if (error.code === 'ECONNREFUSED') {
            return res.status(500).json({ status: 'error', message: 'service unavailable' });
        }

        const { status, data } = error.response;
        return res.status(status).json(data);
    }
}