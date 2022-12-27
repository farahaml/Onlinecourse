//mendefinisikan model
module.exports = (sequelize, DataTypes) => {
    //mendeklarasikan nama model
    const User = sequelize.define('User', 
    //skema dari model
    {
        id: {
            type: DataTypes.INTEGER, 
            autoIncrement: true,
            primaryKey: true,
            allowNull: false
        },
        name: {
            type: DataTypes.STRING,
            allowNull: false
        },
        email: {
            type: DataTypes.STRING,
            allowNull: false,
            unique: true
        },
        password: {
            type: DataTypes.STRING,
            allowNull: false
        },
        role: {
            type: DataTypes.ENUM,
            values: ['admin', 'student'],
            allowNull: false,
            defaultValue: 'student'
           },
           avatar: {
            type: DataTypes.STRING,
            allowNull: true
           },
           profession: {
            type: DataTypes.STRING,
            allowNull: true
           },
           createdAt: {
            field: 'created_at',
            type: DataTypes.DATE,
            allowNull: false
            },
           updatedAt: {
            field: 'updated_at',
            type: DataTypes.DATE,
            allowNull: false
            }
    },
    //optional, set table name
    {
        tableName: 'users',
        timestamps: true
    });
    return User;
}