'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up (queryInterface, Sequelize) {
    await queryInterface.createTable('users', { 
      id: {
        type: Sequelize.INTEGER,
        autoIncrement: true,
        primaryKey: true,
        allowNull: false
      },
      name: {
        type: Sequelize.STRING,
        allowNull: false
       },
       profession: {
        type: Sequelize.STRING,
        allowNull: true
       },
       avatar: {
        type: Sequelize.STRING,
        allowNull: true
       },
       role: {
        type: Sequelize.ENUM,
        values: ['admin', 'student'],
        allowNull: false
       },
        email: {
        type: Sequelize.STRING,
        allowNull: false
       },
       password: {
        type: Sequelize.STRING,
        allowNull: false
       },
       created_at: {
        type: Sequelize.DATE,
        allowNull: false
       },
       updated_at: {
        type: Sequelize.DATE,
        allowNull: false
       }
    });
    /*JIKA MENGGUNAKAN SEQUELIZE VERSI 5  
    await queryInterface.addConstraint('users', ['email'], {
      //akan menampilkan error jika email yang dikirimkan sama
      type: 'unique',
      name: 'UNIQUE_USERS_EMAIL'
    })*/
    
    await queryInterface.addConstraint('users', {
      //akan menampilkan error jika email yang dikirimkan sama
      type: 'unique',
      fields: ['email'],
      name: 'UNIQUE_USERS_EMAIL'
    });
  },

  async down (queryInterface, Sequelize) {
   await queryInterface.dropTable('users');
  }
};
