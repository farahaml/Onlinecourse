const jwt = require('jsonwebtoken');

const JWT_SECRET = 'microser!98';

/*membuat basic token dengan proses syncronous
const token = jwt.sign({ data : { kelas: 'suryamicrosystem' } }, 
JWT_SECRET, 
{ expiresIn: 5 });
console.log(token); */

/* membuat basic token menggunakan proses asyncronous -> tidak menunggu token dieksekusi dan langsung menjalankan code berikutnya
jwt.sign({ data : { kelas: 'suryamicrosystem' } }, JWT_SECRET, { expiresIn: '1h' }, (err, token) => {
    console.log(token);
}); */

const token1 = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJkYXRhIjp7ImtlbGFzIjoic3VyeWFtaWNyb3N5c3RlbSJ9LCJpYXQiOjE2NzMyMzQ5NDksImV4cCI6MTY3MzIzODU0OX0.Ge2hR1ROpn_0v-5bVQtGID6jPrhHOCfqw8_N9mRm1vw';

/* cara 1 memverifikasi token
jwt.verify(token1, JWT_SECRET, (err, decoded) => {
    // mengidentifiksai jenis error
if (err) {
    console.log(error.message);
    return;
}
// jika sudah didecoded
console.log(decoded);
}); */

// cara 2 memverifikasi token
try {
    const decoded = jwt.verify(token1, JWT_SECRET);
    console.log(decoded);
} catch (error) {
    console.log(error.message);
} 