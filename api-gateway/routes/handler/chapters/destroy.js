/* delete chapter data by id */

const apiAdapter = require('../../apiAdapter');

//call config variable
const {
    URL_SERVICE_COURSE
} = process.env;

//call adapter
const api = apiAdapter(URL_SERVICE_COURSE);

module.exports = async (req, res) => {
    try {
        //req chapter id
        const id = req.params.id;
        //req chapter data by id
        const chapter = await api.delete(`/api/chapters/${id}`);

        //success response
        return res.json(chapter.data);
    } catch (error) {
        //if service off
        if (error.code === 'ECONNREFUSED') {
            return res.status(500).json({
                status: 'error',
                message: 'service unavailable'
            });
        }

        const { status, data } = error.response;
        return res.status(status).json(data);
    }
}