/*logout di semua device*/

const apiAdapter = require('../../apiAdapter');

//memanggil sebuah variabel dari config
const {
    URL_SERVICE_USER
} = process.env;

//varibael untuk memanggil adapter
const api = apiAdapter(URL_SERVICE_USER);

module.exports = async (req, res) => {
    try {
        const id = req.user.data.id;
        const user = await api.post(`/users/logout`, { user_id: id });
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