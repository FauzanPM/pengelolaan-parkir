const express = require('express');
const bodyParser = require('body-parser');
const mysql = require('mysql');
const app = express();

app.use(bodyParser.json());

const db = mysql.createConnection({
    host: 'localhost',
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: 'parkir_db'
});

db.connect(err => {
    if (err) {
        console.error('Database connection failed: ' + err.stack);
        return;
    }
    console.log('Database connected!');
});

// Endpoint untuk mendapatkan semua login
app.get('/login', (req, res) => {
    db.query('SELECT * FROM login', (err, results) => {
        if (err) throw err;
        res.json(results);
    });
});

// Endpoint untuk mendapatkan semua kendaraan
app.get('/kendaraan', (req, res) => {
    db.query('SELECT * FROM kendaraan', (err, results) => {
        if (err) throw err;
        res.json(results);
    });
});

// Endpoint untuk mendapatkan semua lokasi
app.get('/lokasi', (req, res) => {
    db.query('SELECT * FROM lokasi', (err, results) => {
        if (err) throw err;
        res.json(results);
    });
});

// Endpoint untuk mendapatkan semua tiket
app.get('/tiket', (req, res) => {
    db.query('SELECT * FROM tiket', (err, results) => {
        if (err) throw err;
        res.json(results);
    });
});

// Endpoint untuk menambah login
app.post('/kendaraan', (req, res) => {
    const { jenis_kendaraan, nama_kendaraan } = req.body;
    if (!jenis_kendaraan || !nama_kendaraan) {
        return res.status(400).json({ error: 'Please provide jenis_kendaraan and nama_kendaraan' });
    }
    const sql = 'INSERT INTO kendaraan (jenis_kendaraan, nama_kendaraan) VALUES (?, ?)';
    db.query(sql, [jenis_kendaraan, nama_kendaraan], (err, results) => {
        if (err) throw err;
        res.json({ id: results.insertId, jenis_kendaraan, nama_kendaraan });
    });
});

// Endpoint untuk menambah kendaraan
app.post('/kendaraan', (req, res) => {
    const { jenis_kendaraan, nama_kendaraan } = req.body;
    const sql = 'INSERT INTO kendaraan (jenis_kendaraan, nama_kendaraan) VALUES (?, ?)';
    db.query(sql, [jenis_kendaraan, nama_kendaraan], (err, results) => {
        if (err) throw err;
        res.json({ id: results.insertId, jenis_kendaraan, nama_kendaraan });
    });
});

// Endpoint untuk menambah lokasi
app.post('/lokasi', (req, res) => {
    const { titik_lokasi, nama_lokasi, ketersediaan } = req.body;
    const sql = 'INSERT INTO lokasi (titik_lokasi, nama_lokasi, ketersediaan) VALUES (?, ?, ?)';
    db.query(sql, [titik_lokasi, nama_lokasi, ketersediaan], (err, results) => {
        if (err) throw err;
        res.json({ id: results.insertId, titik_lokasi, nama_lokasi, ketersediaan });
    });
});

// Endpoint untuk menambah tiket
app.post('/tiket', (req, res) => {
    const { id_kendaraan, id_lokasi, tanggal, status } = req.body;
    const sql = 'INSERT INTO tiket (id_kendaraan, id_lokasi, tanggal, status) VALUES (?, ?, ?, ?)';
    db.query(sql, [id_kendaraan, id_lokasi, tanggal, status], (err, results) => {
        if (err) throw err;
        res.json({ id: results.insertId, id_kendaraan, id_lokasi, tanggal, status });
    });
});

// Endpoint untuk menghapus login
app.delete('/login/:id', (req, res) => {
    const { id } = req.params;
    const sql = 'DELETE FROM login WHERE id_login = ?';
    db.query(sql, [id], (err, results) => {
        if (err) throw err;
        res.json({ message: 'Login deleted', id });
    });
});

// Endpoint untuk menghapus kendaraan
app.delete('/kendaraan/:id', (req, res) => {
    const { id } = req.params;
    const sql = 'DELETE FROM kendaraan WHERE id_kendaraan = ?';
    db.query(sql, [id], (err, results) => {
        if (err) throw err;
        res.json({ message: 'Kendaraan deleted', id });
    });
});

// Endpoint untuk menghapus lokasi
app.delete('/lokasi/:id', (req, res) => {
    const { id } = req.params;
    const sql = 'DELETE FROM lokasi WHERE id_lokasi = ?';
    db.query(sql, [id], (err, results) => {
        if (err) throw err;
        res.json({ message: 'Lokasi deleted', id });
    });
});

// Endpoint untuk menghapus tiket
app.delete('/tiket/:id', (req, res) => {
    const { id } = req.params;
    const sql = 'DELETE FROM tiket WHERE id_tiket = ?';
    db.query(sql, [id], (err, results) => {
        if (err) throw err;
        res.json({ message: 'Tiket deleted', id });
    });
});


const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
});
