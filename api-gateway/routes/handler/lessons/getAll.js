/* show all chapter list */

const apiAdapter = require('../../apiAdapter');

//call config variable
const {
    URL_SERVICE_COURSE
} = process.env;

//call adapter
const api = apiAdapter(URL_SERVICE_COURSE);

module.exports = async (req, res) => {
    try {

        //req lessons data
        const lessons = await api.get('/api/lessons', {
            params: {
                ...req.query
            }
        });

        //succes response
        return res.json(lessons.data);
    } catch (error) {
        if (error.code === 'ECONNREFUSED') {
            //if service off
            return res.status(500).json({
                status: 'error',
                message: 'service unavailable'
            });
        }

        const { status, data } = error.response;
        //error response
        return res.status(status).json(data);
    }
}