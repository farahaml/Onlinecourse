/*untuk menghapus data dan file di database*/

/*upload data*/

const apiAdapter = require('../../apiAdapter');

//memanggil sebuah variabel dari config
const {
    URL_SERVICE_MEDIA
} = process.env;

//varibael untuk memanggil adpter
const api = apiAdapter(URL_SERVICE_MEDIA);

module.exports = async (req, res) => {
    try {
      const id = req.params.id;
      const media = await api.delete(`/media/${id}`);
      //reponse if success
      return res.json(media.data);  
    } catch (error) {

        //when service-media off
        if (error.code === 'ECONNREFUSED') {
            return res.status(500).json({ status: 'error', message: 'service unavailable' });
        }

        const { status, data } = error.response;
        return res.status(status).json(data);
    }
}