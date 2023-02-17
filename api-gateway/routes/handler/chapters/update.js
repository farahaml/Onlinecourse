/* updating chapter data by id*/

const apiAdapter = require('../../apiAdapter');

//call config variable
const {
    URL_SERVICE_COURSE
} = process.env;

//calling api adapter
const api = apiAdapter(URL_SERVICE_COURSE);

module.exports = async (req, res) => {
    try {
        //req id from database
        const id = req.params.id;
        //req chapter data
        const chapter = await api.put(`/api/chapters/${id}`, req.body);

        //success response
        return res.json(chapter.data);
    } catch (error) {
        // if service off
        if (error.code === 'ECONNREFUSED') {
            return res.status(500).json({
                status: 'error',
                message: 'service unavailable'
            });
        }

        //error variable
        const { status, data } = error.response;
        //error response
        return res.status(status).json(data);
    }
}