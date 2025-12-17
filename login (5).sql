-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2025 at 05:40 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `login`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(2) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `password`) VALUES
(1, 'admin@gmail.com', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `attempts`
--

CREATE TABLE `attempts` (
  `id` int(11) NOT NULL,
  `user` varchar(100) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `attempt_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attempts`
--

INSERT INTO `attempts` (`id`, `user`, `subject_id`, `score`, `total`, `attempt_date`) VALUES
(6, 'Trisha', 5, 4, 5, '2025-10-13 15:15:13'),
(7, 'Trisha', 4, 3, 5, '2025-10-13 15:18:29'),
(8, 'Trisha', 3, 2, 5, '2025-10-13 15:19:12'),
(9, 'Trisha', 4, 3, 5, '2025-10-13 15:19:40'),
(10, 'Trisha', 5, 3, 5, '2025-10-13 15:20:17'),
(11, 'Trisha', 2, 3, 5, '2025-10-13 15:21:14'),
(12, 'Krishita', 5, 4, 5, '2025-10-13 15:26:40'),
(13, 'Krishita', 1, 3, 5, '2025-10-13 15:27:19'),
(14, 'Krishita', 2, 4, 5, '2025-10-13 15:28:00'),
(15, 'Krishita', 3, 3, 5, '2025-10-13 15:28:37'),
(16, 'Krishita', 4, 0, 5, '2025-10-13 15:29:10');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `username`, `message`, `submitted_at`) VALUES
(1, 'xyz', 'very nice!!!!!', '2025-08-06 15:52:55'),
(2, 'xyz', 'gooddddd!!!!!!!!!!', '2025-08-10 15:09:53'),
(3, 'Trisha', 'Good!!!', '2025-10-13 15:22:14'),
(4, 'Trisha', 'Well Done!!', '2025-10-13 15:22:23'),
(5, 'Krishita', 'Done!!\r\n', '2025-10-13 15:29:45'),
(6, 'Krishita', 'Very Good!!\r\n', '2025-10-13 15:29:58');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `question` text NOT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `option1` varchar(255) DEFAULT NULL,
  `option2` varchar(255) DEFAULT NULL,
  `option3` varchar(255) DEFAULT NULL,
  `option4` varchar(255) DEFAULT NULL,
  `correct_answer` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `subject_id`, `question`, `subject`, `option1`, `option2`, `option3`, `option4`, `correct_answer`, `created_at`) VALUES
(2, 4, 'iokhj', 'Geography', 'a', 'b', 'c', 'd', 'd', '2025-08-10 14:55:35'),
(3, 3, 'rkgmlfgkermg', 'History', 'a', 'b', 'c', 'd', 'b', '2025-09-04 13:02:30'),
(4, 1, 'What is 5 + 7?', 'Maths', '10', '11', '12', '13', '12', '2025-09-05 12:55:43'),
(5, 1, 'The square root of 81 is?', 'Maths', '9', '8', '7', '6', '9', '2025-09-05 12:55:43'),
(6, 1, 'Which is a prime number?', 'Maths', '21', '29', '33', '39', '29', '2025-09-05 12:55:43'),
(7, 2, 'What is H2O commonly known as?', 'Science', 'Oxygen', 'Hydrogen', 'Water', 'Carbon dioxide', 'Water', '2025-09-05 12:55:43'),
(8, 2, 'Which planet is known as the Red Planet?', 'Science', 'Earth', 'Mars', 'Jupiter', 'Venus', 'Mars', '2025-09-05 12:55:43'),
(9, 2, 'The process of plants making food using sunlight is?', 'Science', 'Respiration', 'Photosynthesis', 'Transpiration', 'Evaporation', 'Photosynthesis', '2025-09-05 12:55:43'),
(10, 3, 'Who was the first President of the United States?', 'History', 'George Washington', 'Abraham Lincoln', 'Thomas Jefferson', 'John Adams', 'George Washington', '2025-09-05 12:55:43'),
(11, 3, 'In which year did World War II end?', 'History', '1945', '1940', '1939', '1950', '1945', '2025-09-05 12:55:43'),
(12, 3, 'Who was known as the Iron Man of India?', 'History', 'Mahatma Gandhi', 'Sardar Vallabhbhai Patel', 'Jawaharlal Nehru', 'Subhash Chandra Bose', 'Sardar Vallabhbhai Patel', '2025-09-05 12:55:43'),
(13, 4, 'Which is the largest continent?', 'Geography', 'Asia', 'Africa', 'Europe', 'Australia', 'Asia', '2025-09-05 12:55:43'),
(14, 4, 'The Sahara Desert is located in which continent?', 'Geography', 'Asia', 'Africa', 'Australia', 'South America', 'Africa', '2025-09-05 12:55:43'),
(15, 4, 'Which is the longest river in the world?', 'Geography', 'Amazon', 'Ganga', 'Nile', 'Yangtze', 'Nile', '2025-09-05 12:55:43'),
(16, NULL, 'axdA', 'Geography', 'A', 'B', 'c', 'd', 'B', '2025-09-09 05:51:33');

-- --------------------------------------------------------

--
-- Table structure for table `questions_english`
--

CREATE TABLE `questions_english` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option1` varchar(255) DEFAULT NULL,
  `option2` varchar(255) DEFAULT NULL,
  `option3` varchar(255) DEFAULT NULL,
  `option4` varchar(255) DEFAULT NULL,
  `correct_answer` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions_english`
--

INSERT INTO `questions_english` (`id`, `question`, `option1`, `option2`, `option3`, `option4`, `correct_answer`, `created_at`) VALUES
(1, 'Which of the following is a synonym of \"Happy\"?', 'Sad', 'Joyful', 'Angry', 'Tired', 'Joyful', '2025-09-09 13:15:06'),
(2, 'Choose the correctly spelled word:', 'Enviroment', 'Environment', 'Enviornment', 'Enviromentt', 'Environment', '2025-09-09 13:15:06'),
(3, 'Identify the verb in the sentence: \"She runs fast.\"', 'She', 'Runs', 'Fast', 'None', 'Runs', '2025-09-09 13:15:06'),
(4, 'Which of the following is an antonym of \"Hot\"?', 'Warm', 'Cold', 'Boiling', 'Heated', 'Cold', '2025-09-09 13:15:06'),
(5, 'Complete the sentence: \"The book is _____ the table.\"', 'In', 'On', 'At', 'Over', 'On', '2025-09-09 13:15:06'),
(6, 'Choose the correct synonym of \"Happy\".', 'Sad', 'Joyful', 'Angry', 'Tired', 'Joyful', '2025-09-17 05:35:39'),
(7, 'Which of the following is a noun?', 'Run', 'Beautiful', 'Table', 'Quickly', 'Table', '2025-09-17 05:35:39'),
(8, 'Fill in the blank: He ___ to school every day.', 'go', 'goes', 'gone', 'going', 'goes', '2025-09-17 05:35:39'),
(9, 'Which is the correct spelling?', 'Enviroment', 'Environment', 'Envirnment', 'Environmnt', 'Environment', '2025-09-17 05:35:39'),
(10, 'Antonym of \"Strong\" is:', 'Weak', 'Powerful', 'Brave', 'Bold', 'Weak', '2025-09-17 05:35:39'),
(11, 'Identify the verb in this sentence: \"The cat sleeps on the sofa.\"', 'Cat', 'Sleeps', 'Sofa', 'The', 'Sleeps', '2025-09-17 05:35:39'),
(12, 'Which article is correct: ___ apple a day keeps the doctor away.', 'A', 'An', 'The', 'No article', 'An', '2025-09-17 05:35:39'),
(13, 'What is the plural of \"Child\"?', 'Childs', 'Children', 'Childes', 'Childern', 'Children', '2025-09-17 05:35:39'),
(14, 'Who is the author of \"Hamlet\"?', 'Charles Dickens', 'William Shakespeare', 'Jane Austen', 'Mark Twain', 'William Shakespeare', '2025-09-17 05:35:39'),
(15, 'Fill in the blank: She is ___ honest girl.', 'a', 'an', 'the', 'no article', 'an', '2025-09-17 05:35:39'),
(16, 'Which of the following is an adjective?', 'Slowly', 'Beautiful', 'Run', 'Quickly', 'Beautiful', '2025-09-17 05:35:39'),
(17, 'Choose the correct past tense: They ___ to the park yesterday.', 'go', 'going', 'went', 'gone', 'went', '2025-09-17 05:35:39'),
(18, 'Find the correct spelling:', 'Recieve', 'Receive', 'Receeve', 'Receve', 'Receive', '2025-09-17 05:35:39'),
(19, 'Which word is a pronoun?', 'Book', 'He', 'Table', 'Run', 'He', '2025-09-17 05:35:39'),
(20, 'Antonym of \"Begin\" is:', 'Start', 'End', 'Open', 'Create', 'End', '2025-09-17 05:35:39');

-- --------------------------------------------------------

--
-- Table structure for table `questions_geography`
--

CREATE TABLE `questions_geography` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option1` varchar(255) DEFAULT NULL,
  `option2` varchar(255) DEFAULT NULL,
  `option3` varchar(255) DEFAULT NULL,
  `option4` varchar(255) DEFAULT NULL,
  `correct_answer` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions_geography`
--

INSERT INTO `questions_geography` (`id`, `question`, `option1`, `option2`, `option3`, `option4`, `correct_answer`, `created_at`) VALUES
(1, 'Which is the largest continent?', 'Africa', 'Asia', 'Europe', 'Australia', 'Asia', '2025-09-17 05:36:59'),
(2, 'Which is the smallest continent?', 'Europe', 'Australia', 'Antarctica', 'South America', 'Australia', '2025-09-17 05:36:59'),
(3, 'Which is the longest river in the world?', 'Amazon', 'Nile', 'Ganga', 'Yangtze', 'Nile', '2025-09-17 05:36:59'),
(4, 'Which is the largest ocean?', 'Indian Ocean', 'Pacific Ocean', 'Atlantic Ocean', 'Arctic Ocean', 'Pacific Ocean', '2025-09-17 05:36:59'),
(5, 'Mount Everest is located in which mountain range?', 'Alps', 'Andes', 'Himalayas', 'Rockies', 'Himalayas', '2025-09-17 05:36:59'),
(6, 'Which country is called the Land of the Rising Sun?', 'India', 'China', 'Japan', 'Korea', 'Japan', '2025-09-17 05:36:59'),
(7, 'Which is the largest desert in the world?', 'Sahara', 'Thar', 'Gobi', 'Kalahari', 'Sahara', '2025-09-17 05:36:59'),
(8, 'The Great Wall of China is visible from which place?', 'Moon', 'Space', 'Mars', 'Sun', 'Space', '2025-09-17 05:36:59'),
(9, 'Which is the capital of Australia?', 'Sydney', 'Melbourne', 'Canberra', 'Perth', 'Canberra', '2025-09-17 05:36:59'),
(10, 'Which is the largest country in the world by area?', 'USA', 'Russia', 'Canada', 'China', 'Russia', '2025-09-17 05:36:59'),
(11, 'Which river flows through Egypt?', 'Amazon', 'Nile', 'Mississippi', 'Yangtze', 'Nile', '2025-09-17 05:36:59'),
(12, 'Which is the coldest place on Earth?', 'Alaska', 'Antarctica', 'Siberia', 'Greenland', 'Antarctica', '2025-09-17 05:36:59'),
(13, 'Which continent has no permanent human population?', 'Australia', 'Europe', 'Antarctica', 'South America', 'Antarctica', '2025-09-17 05:36:59'),
(14, 'Which country has the most population?', 'USA', 'India', 'China', 'Brazil', 'China', '2025-09-17 05:36:59'),
(15, 'Which is the highest waterfall in the world?', 'Niagara Falls', 'Angel Falls', 'Victoria Falls', 'Jog Falls', 'Angel Falls', '2025-09-17 05:36:59'),
(16, 'Which line divides Earth into Northern and Southern Hemisphere?', 'Prime Meridian', 'Equator', 'Tropic of Cancer', 'Longitude', 'Equator', '2025-09-17 05:36:59'),
(17, 'Which is the capital of Canada?', 'Toronto', 'Vancouver', 'Ottawa', 'Montreal', 'Ottawa', '2025-09-17 05:36:59'),
(18, 'Which sea is the saltiest?', 'Dead Sea', 'Red Sea', 'Caspian Sea', 'Mediterranean Sea', 'Dead Sea', '2025-09-17 05:36:59'),
(19, 'Which country is known as the Land of Thousand Lakes?', 'India', 'Finland', 'Sweden', 'Norway', 'Finland', '2025-09-17 05:36:59'),
(20, 'Which desert is in India?', 'Sahara', 'Kalahari', 'Gobi', 'Thar', 'Thar', '2025-09-17 05:36:59');

-- --------------------------------------------------------

--
-- Table structure for table `questions_history`
--

CREATE TABLE `questions_history` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option1` varchar(255) DEFAULT NULL,
  `option2` varchar(255) DEFAULT NULL,
  `option3` varchar(255) DEFAULT NULL,
  `option4` varchar(255) DEFAULT NULL,
  `correct_answer` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions_history`
--

INSERT INTO `questions_history` (`id`, `question`, `option1`, `option2`, `option3`, `option4`, `correct_answer`, `created_at`) VALUES
(1, 'Who was the first President of India?', 'Mahatma Gandhi', 'Rajendra Prasad', 'Jawaharlal Nehru', 'Sardar Patel', 'Rajendra Prasad', '2025-09-17 05:33:36'),
(2, 'Which year did India gain independence?', '1945', '1946', '1947', '1948', '1947', '2025-09-17 05:33:36'),
(3, 'Who is known as the Father of the Nation in India?', 'Subhas Chandra Bose', 'Bhagat Singh', 'Mahatma Gandhi', 'Jawaharlal Nehru', 'Mahatma Gandhi', '2025-09-17 05:33:36'),
(4, 'Which empire built the Taj Mahal?', 'Gupta Empire', 'Mughal Empire', 'Maurya Empire', 'British Empire', 'Mughal Empire', '2025-09-17 05:33:36'),
(5, 'Who was the founder of the Maurya Empire?', 'Ashoka', 'Chandragupta Maurya', 'Bindusara', 'Harsha', 'Chandragupta Maurya', '2025-09-17 05:33:36'),
(6, 'Who was the first Emperor of the Mughal dynasty?', 'Akbar', 'Babur', 'Humayun', 'Aurangzeb', 'Babur', '2025-09-17 05:34:28'),
(7, 'In which year did the Revolt of 1857 take place?', '1856', '1857', '1858', '1860', '1857', '2025-09-17 05:34:28'),
(8, 'Who was known as the Iron Man of India?', 'Bal Gangadhar Tilak', 'Sardar Vallabhbhai Patel', 'Bhagat Singh', 'Subhas Chandra Bose', 'Sardar Vallabhbhai Patel', '2025-09-17 05:34:28'),
(9, 'Which Indian king fought against Alexander the Great?', 'Chandragupta Maurya', 'Porus', 'Ashoka', 'Harsha', 'Porus', '2025-09-17 05:34:28'),
(10, 'Who was the last Governor-General of independent India?', 'C. Rajagopalachari', 'Lord Mountbatten', 'Warren Hastings', 'Lord Dalhousie', 'C. Rajagopalachari', '2025-09-17 05:34:28'),
(11, 'Who gave the slogan \"Swaraj is my birthright and I shall have it\"?', 'Jawaharlal Nehru', 'Mahatma Gandhi', 'Bal Gangadhar Tilak', 'Lala Lajpat Rai', 'Bal Gangadhar Tilak', '2025-09-17 05:34:28'),
(12, 'Which battle laid the foundation of the Mughal Empire in India?', 'Battle of Haldighati', 'Battle of Panipat (1526)', 'Battle of Plassey', 'Battle of Buxar', 'Battle of Panipat (1526)', '2025-09-17 05:34:28'),
(13, 'Who was the first woman ruler of Delhi?', 'Rani Lakshmi Bai', 'Razia Sultana', 'Ahilyabai Holkar', 'Nur Jahan', 'Razia Sultana', '2025-09-17 05:34:28'),
(14, 'The Quit India Movement was launched in which year?', '1939', '1940', '1942', '1945', '1942', '2025-09-17 05:34:28'),
(15, 'Who wrote the Indian National Anthem?', 'Bankim Chandra Chatterjee', 'Rabindranath Tagore', 'Sarojini Naidu', 'Subhas Chandra Bose', 'Rabindranath Tagore', '2025-09-17 05:34:28'),
(16, 'Which viceroy is associated with the Partition of Bengal (1905)?', 'Lord Curzon', 'Lord Mountbatten', 'Lord Dalhousie', 'Lord Wellesley', 'Lord Curzon', '2025-09-17 05:34:28'),
(17, 'Who founded the Indian National Congress (INC)?', 'Mahatma Gandhi', 'Dadabhai Naoroji', 'A.O. Hume', 'Bal Gangadhar Tilak', 'A.O. Hume', '2025-09-17 05:34:28'),
(18, 'Which freedom fighter is known as \"Netaji\"?', 'Bhagat Singh', 'Subhas Chandra Bose', 'Sukhdev', 'Rajguru', 'Subhas Chandra Bose', '2025-09-17 05:34:28'),
(19, 'Who built the Red Fort in Delhi?', 'Shah Jahan', 'Akbar', 'Aurangzeb', 'Humayun', 'Shah Jahan', '2025-09-17 05:34:28'),
(20, 'The Jallianwala Bagh massacre took place in which city?', 'Delhi', 'Amritsar', 'Lahore', 'Kanpur', 'Amritsar', '2025-09-17 05:34:28');

-- --------------------------------------------------------

--
-- Table structure for table `questions_maths`
--

CREATE TABLE `questions_maths` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option1` varchar(255) DEFAULT NULL,
  `option2` varchar(255) DEFAULT NULL,
  `option3` varchar(255) DEFAULT NULL,
  `option4` varchar(255) DEFAULT NULL,
  `correct_answer` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions_maths`
--

INSERT INTO `questions_maths` (`id`, `question`, `option1`, `option2`, `option3`, `option4`, `correct_answer`, `created_at`) VALUES
(2, 'What is 12 + 8?', '18', '20', '22', '24', '20', '2025-09-09 06:23:03'),
(3, 'What is the square root of 81?', '7', '8', '9', '10', '9', '2025-09-09 06:23:03'),
(4, 'Solve: 15 × 6 = ?', '80', '85', '90', '95', '90', '2025-09-09 06:23:03'),
(5, 'What is the value of π (approx)?', '3.12', '3.14', '3.15', '3.16', '3.14', '2025-09-09 06:23:03'),
(6, 'If a triangle has sides 3, 4, 5, it is a?', 'Right-angled triangle', 'Equilateral triangle', 'Isosceles triangle', 'Scalene triangle', 'Right-angled triangle', '2025-09-09 06:23:03'),
(7, 'What is 25% of 200?', '25', '40', '50', '60', '50', '2025-09-09 06:23:03'),
(8, 'Simplify: 2(3 + 4)', '14', '16', '12', '18', '14', '2025-09-09 06:23:03'),
(9, 'Convert 0.75 into fraction?', '3/2', '1/2', '3/4', '2/3', '3/4', '2025-09-09 06:23:03'),
(10, 'What is the perimeter of a square with side 5 cm?', '10 cm', '15 cm', '20 cm', '25 cm', '20 cm', '2025-09-09 06:23:03'),
(11, 'If x = 5, what is the value of 2x²?', '25', '30', '40', '50', '50', '2025-09-09 06:23:03'),
(12, 'What is 12 × 8?', '96', '86', '108', '98', '96', '2025-09-17 05:38:19'),
(13, 'Simplify: 25 ÷ 5 × 2', '5', '10', '15', '20', '10', '2025-09-17 05:38:19'),
(14, 'The square root of 144 is?', '10', '12', '14', '16', '12', '2025-09-17 05:38:19'),
(15, 'If a triangle has sides 3, 4, 5, it is a?', 'Equilateral', 'Scalene', 'Isosceles', 'Right-angled', 'Right-angled', '2025-09-17 05:38:19'),
(16, 'Solve: 15 + (6 ÷ 2)', '18', '21', '20', '19', '18', '2025-09-17 05:38:19'),
(17, 'Value of π (approx)?', '2.14', '3.14', '3.41', '4.13', '3.14', '2025-09-17 05:38:19'),
(18, 'What is 7²?', '14', '49', '56', '77', '49', '2025-09-17 05:38:19'),
(19, 'If 5x = 20, find x.', '2', '3', '4', '5', '4', '2025-09-17 05:38:19'),
(20, 'Perimeter of square with side 9 cm?', '18 cm', '27 cm', '36 cm', '81 cm', '36 cm', '2025-09-17 05:38:19'),
(21, 'Area of rectangle with length 12 and width 5?', '60', '65', '70', '75', '60', '2025-09-17 05:38:19'),
(22, 'What is 100 ÷ 25?', '2', '3', '4', '5', '4', '2025-09-17 05:38:19');

-- --------------------------------------------------------

--
-- Table structure for table `questions_science`
--

CREATE TABLE `questions_science` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option1` varchar(255) DEFAULT NULL,
  `option2` varchar(255) DEFAULT NULL,
  `option3` varchar(255) DEFAULT NULL,
  `option4` varchar(255) DEFAULT NULL,
  `correct_answer` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions_science`
--

INSERT INTO `questions_science` (`id`, `question`, `option1`, `option2`, `option3`, `option4`, `correct_answer`, `created_at`) VALUES
(2, 'What is the center of an atom called?', 'Proton', 'Electron', 'Nucleus', 'Neutron', 'Nucleus', '2025-09-17 05:39:44'),
(3, 'Which planet is closest to the Sun?', 'Venus', 'Mercury', 'Earth', 'Mars', 'Mercury', '2025-09-17 05:39:44'),
(4, 'What gas do plants release during photosynthesis?', 'Oxygen', 'Carbon dioxide', 'Nitrogen', 'Hydrogen', 'Oxygen', '2025-09-17 05:39:44'),
(5, 'Which organ helps in digestion by producing bile?', 'Liver', 'Stomach', 'Pancreas', 'Kidney', 'Liver', '2025-09-17 05:39:44'),
(6, 'What is the unit of electric current?', 'Volt', 'Watt', 'Ampere', 'Ohm', 'Ampere', '2025-09-17 05:39:44'),
(7, 'Which is the largest organ of the human body?', 'Heart', 'Skin', 'Liver', 'Lungs', 'Skin', '2025-09-17 05:39:44'),
(8, 'What gas do humans inhale to survive?', 'Carbon dioxide', 'Oxygen', 'Nitrogen', 'Hydrogen', 'Oxygen', '2025-09-17 05:39:44'),
(9, 'Which planet is known as the Red Planet?', 'Mars', 'Venus', 'Jupiter', 'Saturn', 'Mars', '2025-09-17 05:39:44'),
(10, 'What is the boiling point of water at sea level?', '90°C', '100°C', '110°C', '120°C', '100°C', '2025-09-17 05:39:44'),
(11, 'Which vitamin is produced when sunlight hits the skin?', 'Vitamin A', 'Vitamin B', 'Vitamin C', 'Vitamin D', 'Vitamin D', '2025-09-17 05:39:44'),
(12, 'What is the hardest natural substance on Earth?', 'Iron', 'Gold', 'Diamond', 'Steel', 'Diamond', '2025-09-17 05:39:44'),
(13, 'Which blood cells help fight infections?', 'Red blood cells', 'White blood cells', 'Platelets', 'Plasma', 'White blood cells', '2025-09-17 05:39:44'),
(14, 'What part of the plant conducts photosynthesis?', 'Stem', 'Roots', 'Leaves', 'Flowers', 'Leaves', '2025-09-17 05:39:44'),
(15, 'Which gas do humans exhale?', 'Oxygen', 'Carbon dioxide', 'Nitrogen', 'Helium', 'Carbon dioxide', '2025-09-17 05:39:44'),
(16, 'What is the chemical symbol for water?', 'O2', 'CO2', 'H2O', 'NaCl', 'H2O', '2025-09-17 05:39:44'),
(17, 'How many planets are in the Solar System?', '7', '8', '9', '10', '8', '2025-09-17 05:39:44'),
(18, 'What is the nearest star to Earth?', 'Mars', 'Moon', 'Sun', 'Venus', 'Sun', '2025-09-17 05:39:44'),
(19, 'Which force pulls objects towards Earth?', 'Magnetism', 'Friction', 'Gravity', 'Electricity', 'Gravity', '2025-09-17 05:39:44'),
(20, 'What part of the human body controls the nervous system?', 'Heart', 'Brain', 'Lungs', 'Kidneys', 'Brain', '2025-09-17 05:39:44'),
(21, 'Which planet is the largest in the Solar System?', 'Earth', 'Jupiter', 'Saturn', 'Neptune', 'Jupiter', '2025-09-17 05:39:44');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `description`, `image`) VALUES
(1, 'Maths', 'Covers Algebra, Arithmetic, Geometry and more.', NULL),
(2, 'Science', 'Physics, Chemistry, Biology and General Science.', NULL),
(3, 'History', 'Ancient, Medieval and Modern history topics.', NULL),
(4, 'Geography', 'Earth, maps, continents, climates and more.', NULL),
(5, 'English', 'Grammar, comprehension, and vocabulary.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_pic` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `password`, `created_at`, `profile_pic`) VALUES
(2, 'Trisha', 'xyz@gmail.com', 'Trisha', '456', '2025-08-06 15:50:46', '1754838571_1.jpg'),
(4, 'Krishita', 'krishi@gmail.com', 'Krishita', '123', '2025-10-13 15:24:07', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attempts`
--
ALTER TABLE `attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_subject` (`subject_id`);

--
-- Indexes for table `questions_english`
--
ALTER TABLE `questions_english`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions_geography`
--
ALTER TABLE `questions_geography`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions_history`
--
ALTER TABLE `questions_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions_maths`
--
ALTER TABLE `questions_maths`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions_science`
--
ALTER TABLE `questions_science`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attempts`
--
ALTER TABLE `attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `questions_english`
--
ALTER TABLE `questions_english`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `questions_geography`
--
ALTER TABLE `questions_geography`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `questions_history`
--
ALTER TABLE `questions_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `questions_maths`
--
ALTER TABLE `questions_maths`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `questions_science`
--
ALTER TABLE `questions_science`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `fk_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
