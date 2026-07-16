-- 1. Create and select the database
CREATE DATABASE IF NOT EXISTS college_db;
USE college_db;

-- 2. Drop table if exists 
DROP TABLE IF EXISTS students;

-- 3. Create students table
CREATE TABLE students (
    id    INT(11)      NOT NULL AUTO_INCREMENT,
    name  VARCHAR(100) NOT NULL,
    age   INT(3)       NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SELECT * FROM students;
ALTER TABLE students
ADD roll_no VARCHAR(20) UNIQUE,
ADD phone VARCHAR(20),
ADD department VARCHAR(100),
ADD semester INT,
ADD cgpa DECIMAL(3,2),
ADD created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

INSERT INTO students (roll_no, name, age, email, phone, department, semester, cgpa) VALUES
('1101', 'Ayesha Khan', 18, 'ayesha.khan@student.lcwu.edu.pk', '0300-2867825', 'Computer Science', 1, 3.85),
('1102', 'Fatima Malik', 19, 'fatima.malik@student.lcwu.edu.pk', '0301-1419610', 'Computer Science', 1, 3.62),
('1201', 'Zainab Ali', 18, 'zainab.ali@student.lcwu.edu.pk', '0302-5614226', 'Computer Science', 2, 3.10),
('1202', 'Maryam Ahmed', 19, 'maryam.ahmed@student.lcwu.edu.pk', '0303-5108603', 'Computer Science', 2, 2.78),
('1301', 'Noor Raza', 19, 'noor.raza@student.lcwu.edu.pk', '0311-4744854', 'Computer Science', 3, 2.35),
('1302', 'Hira Iqbal', 20, 'hira.iqbal@student.lcwu.edu.pk', '0321-3341057', 'Computer Science', 3, 3.91),
('1401', 'Mahnoor Sheikh', 19, 'mahnoor.sheikh@student.lcwu.edu.pk', '0331-2719583', 'Computer Science', 4, 2.95),
('1402', 'Eman Butt', 20, 'eman.butt@student.lcwu.edu.pk', '0333-2458591', 'Computer Science', 4, 3.40),
('1501', 'Iqra Chaudhry', 20, 'iqra.chaudhry@student.lcwu.edu.pk', '0340-8078673', 'Computer Science', 5, 2.10),
('1502', 'Areeba Hassan', 21, 'areeba.hassan@student.lcwu.edu.pk', '0345-1533224', 'Computer Science', 5, 3.75),
('1601', 'Alina Farooq', 20, 'alina.farooq@student.lcwu.edu.pk', '0300-1499914', 'Computer Science', 6, 2.60),
('1602', 'Anaya Siddiqui', 21, 'anaya.siddiqui@student.lcwu.edu.pk', '0301-2571945', 'Computer Science', 6, 3.20),
('1701', 'Inaya Qureshi', 21, 'inaya.qureshi@student.lcwu.edu.pk', '0302-4668136', 'Computer Science', 7, 3.85),
('1702', 'Dua Awan', 22, 'dua.awan@student.lcwu.edu.pk', '0303-4903402', 'Computer Science', 7, 3.62),
('1801', 'Laiba Javed', 22, 'laiba.javed@student.lcwu.edu.pk', '0311-9478454', 'Computer Science', 8, 3.10),
('1802', 'Hafsa Nawaz', 23, 'hafsa.nawaz@student.lcwu.edu.pk', '0321-1445199', 'Computer Science', 8, 2.78),

('3101', 'Aiman Saeed', 18, 'aiman.saeed@student.lcwu.edu.pk', '0331-4335942', 'Software Engineering', 1, 2.35),
('3102', 'Rimsha Rashid', 19, 'rimsha.rashid@student.lcwu.edu.pk', '0333-8038374', 'Software Engineering', 1, 3.91),
('3201', 'Saira Yousaf', 18, 'saira.yousaf@student.lcwu.edu.pk', '0340-4698379', 'Software Engineering', 2, 2.95),
('3202', 'Sana Bhatti', 19, 'sana.bhatti@student.lcwu.edu.pk', '0345-8536477', 'Software Engineering', 2, 3.40),
('3301', 'Maham Cheema', 19, 'maham.cheema@student.lcwu.edu.pk', '0300-5667265', 'Software Engineering', 3, 2.10),
('3302', 'Kinza Gill', 20, 'kinza.gill@student.lcwu.edu.pk', '0301-1109031', 'Software Engineering', 3, 3.75),
('3401', 'Komal Mirza', 19, 'komal.mirza@student.lcwu.edu.pk', '0302-3678638', 'Software Engineering', 4, 2.60),
('3402', 'Mehak Baig', 20, 'mehak.baig@student.lcwu.edu.pk', '0303-8090293', 'Software Engineering', 4, 3.20),
('3501', 'Zara Warraich', 20, 'zara.warraich@student.lcwu.edu.pk', '0311-6708456', 'Software Engineering', 5, 3.85),
('3502', 'Amna Anwar', 21, 'amna.anwar@student.lcwu.edu.pk', '0321-5661907', 'Software Engineering', 5, 3.62),
('3601', 'Rabia Aslam', 20, 'rabia.aslam@student.lcwu.edu.pk', '0331-3608513', 'Software Engineering', 6, 3.10),
('3602', 'Sidra Riaz', 21, 'sidra.riaz@student.lcwu.edu.pk', '0333-4612365', 'Software Engineering', 6, 2.78),
('3701', 'Minal Shafiq', 21, 'minal.shafiq@student.lcwu.edu.pk', '0340-6647119', 'Software Engineering', 7, 2.35),
('3702', 'Hania Latif', 22, 'hania.latif@student.lcwu.edu.pk', '0345-2714803', 'Software Engineering', 7, 3.91),
('3801', 'Kiran Khan', 22, 'kiran.khan@student.lcwu.edu.pk', '0300-2556017', 'Software Engineering', 8, 2.95),
('3802', 'Nida Malik', 23, 'nida.malik@student.lcwu.edu.pk', '0301-7374122', 'Software Engineering', 8, 3.40),

('2101', 'Aleena Ali', 18, 'aleena.ali@student.lcwu.edu.pk', '0302-2622631', 'Information Technology', 1, 2.10),
('2102', 'Momina Ahmed', 19, 'momina.ahmed@student.lcwu.edu.pk', '0303-7022674', 'Information Technology', 1, 3.75),
('2201', 'Bushra Raza', 18, 'bushra.raza@student.lcwu.edu.pk', '0311-6770619', 'Information Technology', 2, 2.60),
('2202', 'Saba Iqbal', 19, 'saba.iqbal@student.lcwu.edu.pk', '0321-5437923', 'Information Technology', 2, 3.20),
('2301', 'Anum Sheikh', 19, 'anum.sheikh@student.lcwu.edu.pk', '0331-1728977', 'Information Technology', 3, 3.85),
('2302', 'Sehrish Butt', 20, 'sehrish.butt@student.lcwu.edu.pk', '0333-8707870', 'Information Technology', 3, 3.62),
('2401', 'Muskan Chaudhry', 19, 'muskan.chaudhry@student.lcwu.edu.pk', '0340-9996414', 'Information Technology', 4, 3.10),
('2402', 'Nimra Hassan', 20, 'nimra.hassan@student.lcwu.edu.pk', '0345-3094235', 'Information Technology', 4, 2.78),
('2501', 'Maliha Farooq', 20, 'maliha.farooq@student.lcwu.edu.pk', '0300-7350753', 'Information Technology', 5, 2.35),
('2502', 'Asma Siddiqui', 21, 'asma.siddiqui@student.lcwu.edu.pk', '0301-2322047', 'Information Technology', 5, 3.91),
('2601', 'Rida Qureshi', 20, 'rida.qureshi@student.lcwu.edu.pk', '0302-5918715', 'Information Technology', 6, 2.95),
('2602', 'Hoorain Awan', 21, 'hoorain.awan@student.lcwu.edu.pk', '0303-7067228', 'Information Technology', 6, 3.40),
('2701', 'Bisma Javed', 21, 'bisma.javed@student.lcwu.edu.pk', '0311-4226067', 'Information Technology', 7, 2.10),
('2702', 'Fiza Nawaz', 22, 'fiza.nawaz@student.lcwu.edu.pk', '0321-2166941', 'Information Technology', 7, 3.75),
('2801', 'Mahwish Saeed', 22, 'mahwish.saeed@student.lcwu.edu.pk', '0331-1768805', 'Information Technology', 8, 2.60),
('2802', 'Sonia Rashid', 23, 'sonia.rashid@student.lcwu.edu.pk', '0333-4823498', 'Information Technology', 8, 3.20),

('0101', 'Shanza Yousaf', 18, 'shanza.yousaf@student.lcwu.edu.pk', '0340-5855124', 'Artificial Intelligence', 1, 3.85),
('0102', 'Arooba Bhatti', 19, 'arooba.bhatti@student.lcwu.edu.pk', '0345-2338687', 'Artificial Intelligence', 1, 3.62),
('0201', 'Ayesha Cheema', 18, 'ayesha.cheema@student.lcwu.edu.pk', '0300-4905582', 'Artificial Intelligence', 2, 3.10),
('0202', 'Fatima Gill', 19, 'fatima.gill@student.lcwu.edu.pk', '0301-2694522', 'Artificial Intelligence', 2, 2.78),
('0301', 'Zainab Mirza', 19, 'zainab.mirza@student.lcwu.edu.pk', '0302-7377459', 'Artificial Intelligence', 3, 2.35),
('0302', 'Maryam Baig', 20, 'maryam.baig@student.lcwu.edu.pk', '0303-5663623', 'Artificial Intelligence', 3, 3.91),
('0401', 'Noor Warraich', 19, 'noor.warraich@student.lcwu.edu.pk', '0311-8606962', 'Artificial Intelligence', 4, 2.95),
('0402', 'Hira Anwar', 20, 'hira.anwar@student.lcwu.edu.pk', '0321-7120868', 'Artificial Intelligence', 4, 3.40),
('0501', 'Mahnoor Aslam', 20, 'mahnoor.aslam@student.lcwu.edu.pk', '0331-3728882', 'Artificial Intelligence', 5, 2.10)
('1803', 'Mehwish Khan', 22, 'mehwish.khan@student.lcwu.edu.pk', '0300-5482716', 'Computer Science', 8, 3.74),
('1804', 'Sana Tariq', 23, 'sana.tariq@student.lcwu.edu.pk', '0301-6298457', 'Computer Science', 8, 3.28),
('1805', 'Kainat Aslam', 22, 'kainat.aslam@student.lcwu.edu.pk', '0302-8174536', 'Computer Science', 8, 3.91),
('1806', 'Muneeba Iqbal', 23, 'muneeba.iqbal@student.lcwu.edu.pk', '0303-4763185', 'Computer Science', 8, 2.97),
('1807', 'Rabia Javed', 22, 'rabia.javed@student.lcwu.edu.pk', '0311-5482763', 'Computer Science', 8, 3.52),
('3103', 'Arooj Fatima', 23, 'arooj.fatima@student.lcwu.edu.pk', '0321-6182457', 'Software Engineering', 1, 3.86),
('3104', 'Komal Ashfaq', 22, 'komal.ashfaq@student.lcwu.edu.pk', '0331-4758263', 'Software Engineering', 1, 3.33),
('3703', 'Sidra Khalid', 23, 'sidra.khalid@student.lcwu.edu.pk', '0333-8271645', 'Software Engineering', 7, 3.18),
('3803', 'Nimra Yousaf', 22, 'nimra.yousaf@student.lcwu.edu.pk', '0340-5627814', 'Software Engineering', 8, 3.67),
('3804', 'Hania Raza', 23, 'hania.raza@student.lcwu.edu.pk', '0345-4736281', 'Software Engineering', 8, 2.88),
('2503', 'Momal Ahmed', 22, 'momal.ahmed@student.lcwu.edu.pk', '0300-7182453', 'Information Technology', 5, 3.79),
('2504', 'Fariha Noor', 23, 'fariha.noor@student.lcwu.edu.pk', '0301-5284167', 'Information Technology', 5, 3.42),
('2803', 'Ayesha Riaz', 22, 'ayesha.riaz@student.lcwu.edu.pk', '0302-8472516', 'Information Technology', 8, 2.95),
('2804', 'Maham Akhtar', 23, 'maham.akhtar@student.lcwu.edu.pk', '0303-6185274', 'Information Technology', 8, 3.61),
('2805', 'Laiba Shah', 22, 'laiba.shah@student.lcwu.edu.pk', '0311-4837265', 'Information Technology', 8, 3.11),

('0403', 'Esha Malik', 22, 'esha.malik@student.lcwu.edu.pk', '0321-5718246', 'Artificial Intelligence', 4, 3.87),
('0503', 'Sehrish Khan', 23, 'sehrish.khan@student.lcwu.edu.pk', '0331-8264175', 'Artificial Intelligence', 5, 3.24),
('0303', 'Maliha Javed', 22, 'maliha.javed@student.lcwu.edu.pk', '0333-5182746', 'Artificial Intelligence', 3, 3.55),
('0803', 'Hoorain Fatima', 23, 'hoorain.fatima@student.lcwu.edu.pk', '0340-2718463', 'Artificial Intelligence', 8, 3.08),
('0804', 'Anum Raza', 22, 'anum.raza@student.lcwu.edu.pk', '0345-6842175', 'Artificial Intelligence', 8, 3.93);