'use strict';

//memanggil bcrypt
const bcrypt = require('bcrypt');

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up (queryInterface, Sequelize) {
  await queryInterface.bulkInsert('users', [{
    name: "adlina",
    profession: "Admin Micro",
    role: "admin",
    email: "adlina1234@gmail.com",
    //password perlu di hash terlebih dahulu di terminal menggunakan bcrypt
    password: await bcrypt.hash('sandi123', 10),
    created_at: new Date(),
    updated_at: new Date()
 },
 {
  name: "farah",
  profession: "Front End Developer",
  role: "student",
  email: "farah1234@gmail.com",
  password: await bcrypt.hash('sandiku123', 10),
  created_at: new Date(),
  updated_at: new Date()
}
]);
  },

  //menghapus semua data di table users, berjalan pada command undo di terminal
  async down (queryInterface, Sequelize) {
    await queryInterface.bulkDelete('users', null, {});
  }
};
