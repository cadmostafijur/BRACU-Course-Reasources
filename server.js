const express = require('express');
const bodyParser = require('body-parser');
const mysql = require('mysql');
const path = require('path');
const app = express();
const port = 3000;

app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());
app.use(express.static('public'));

// Create connection to MySQL database
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'bracu_course_resources'
});

db.connect((err) => {
    if (err) {
        throw err;
    }
    console.log('MySQL connected...');
});

// Route to handle course submission
app.post('/submit-course', (req, res) => {
    const { courseName, semester, playlistLink, driveLink, submitterName } = req.body;
    const sql = 'INSERT INTO courses (course_name, semester, playlist_link, drive_link, submitter_name) VALUES (?, ?, ?, ?, ?)';
    db.query(sql, [courseName, semester, playlistLink, driveLink, submitterName], (err, result) => {
        if (err) throw err;
        res.send('Course submitted successfully.');
    });
});

app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

app.listen(port, () => {
    console.log(`Server running at http://localhost:${port}/`);
});
