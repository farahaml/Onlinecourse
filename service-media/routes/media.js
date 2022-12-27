const express = require('express');
const router = express.Router();
const isBase64 = require('is-base64');
const base64Img = require('base64-img');
//library, file system
const fs = require('fs');

const { Media } = require('../models');

//list image
router.get('/', async (req, res) => {
  const media = await Media.findAll({
    //called attributes
    attributes: ['id', 'image']
  });

  const mappedMedia = media.map ((m) => {
    m.image = `${req.get('host')}/${m.image}`;
    return m;
  })

  return res.json({
      status: 'success',
      data: mappedMedia
  });
});
//list image end here


router.post('/', (req, res) => {
  const image = req.body.image;

if (!isBase64(image, { mimeRequired: true })) { 
  return res.status(400).json({ status: 'error', message: 'invalid base64' });
}

base64Img.img(image, './public/images', Date.now(), async (err, filepath) => {
  if (err) {
    return res.status(400).json({ status: 'error', message: err.message });
  }

  const filename = filepath.split("\\").pop().split("/").pop();

  const media = await Media.create({ image: `images/${filename}` });

  return res.json({
    status: 'success',
    data: {
      id: media.id,
      image: `${req.get('host')}/images/${filename}`,
    }
  });
})
});

//API delete image
router.delete('/:id', async (req, res) => {
  const id = req.params.id;
  
  //checking id in database
  const media = await Media.findByPk(id);

  //if there's no media
  if (!media) {
      return res.status(404).json({ status: 'error', message:'media not found' })
  }

  //if there's media, it will be deleted even in database
  fs.unlink(`./public/${media.image}`, async (err) => {
    //if there's an error
    if (err) {
      return res.status(404).json({ status: 'error', message: err.message})
    }

    //deleted media
    await media.destroy();

    //success deleted
    return res.json({
      status: 'success',
      message: 'image deleted'
    })
  });
});

module.exports = router;
