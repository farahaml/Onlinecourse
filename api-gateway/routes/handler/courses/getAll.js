/*show all data*/

const apiAdapter = require('../../apiAdapter');

//memanggil sebuah variabel dari config
const {
    URL_SERVICE_COURSE,
    HOSTNAME
} = process.env;

//varibael untuk memanggil adapter
const api = apiAdapter(URL_SERVICE_COURSE);

module.exports = async (req, res) => {
    try {
      const courses = await api.get('/api/courses', {
        params: {
            ...req.query,
            status: 'published'
        }
      });

      const coursesData = courses.data;
      const firstPage = coursesData.data.first_page_url.split('?').pop();
      const lastPage = coursesData.data.last_page_url.split('?').pop();

      coursesData.data.first_page_url = `${HOSTNAME}/courses?${firstPage}`;
      coursesData.data.last_page_url = `${HOSTNAME}/courses?${lastPage}`;

      if (coursesData.data.next_page_url) {
        const nextPage = coursesData.data.next_page_url.split('?').pop();
        coursesData.data.next_page_url = `${HOSTNAME}/courses?${nextPage}`;
      }

      if (coursesData.data.prev_page_url) {
        const prevPage = coursesData.data.prev_page_url.split('?').pop();
        coursesData.data.prev_page_url = `${HOSTNAME}/courses?${prevPage}`;
      }

      coursesData.data.path = `${HOSTNAME}/courses`;

      //reponse if success
      return res.json(coursesData);  
    } catch (err) {
        //when service-course off
        if (error.code === 'ECONNREFUSED') {
            return res.status(500).json({ status: 'error', message: 'service unavailable' });
        }

        const { status, data } = error.code;
        return res.status(status).json(data);
    }
}