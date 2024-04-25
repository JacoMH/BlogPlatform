-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2024 at 05:45 AM
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
-- Database: `blogplatform`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogpost`
--

CREATE TABLE `blogpost` (
  `userID` int(11) NOT NULL,
  `blogPostID` int(11) NOT NULL,
  `likesOnPost` int(11) NOT NULL,
  `commentsEnabled` text NOT NULL,
  `NumOfComments` int(11) NOT NULL,
  `BlogPostContext` text NOT NULL,
  `DateAndTime` text NOT NULL,
  `blogPostText` text NOT NULL,
  `blogPostImage` text NOT NULL,
  `blogPostLink` text NOT NULL,
  `blogPostVideo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blogpost`
--

INSERT INTO `blogpost` (`userID`, `blogPostID`, `likesOnPost`, `commentsEnabled`, `NumOfComments`, `BlogPostContext`, `DateAndTime`, `blogPostText`, `blogPostImage`, `blogPostLink`, `blogPostVideo`) VALUES
(1, 15, 0, 'on', 2, 'they are waffling', '2024-04-24 01:34:05', 'dddddzdzdzddz', '', '', ''),
(1, 16, 2, 'on', 0, 'nice', '2024-04-24 01:34:55', 'posty', '', '', ''),
(1, 17, 2, 'on', 1, 'oasis music', '2024-04-24 01:49:23', 'oasis :)', '', '', 'https://www.youtube.com/watch?v=CtLdWZ1IV6o'),
(22, 22, 1, 'on', 1, '', '2024-04-25 03:17:27', 'hello', 'https://buffer.com/library/content/images/size/w1200/2023/10/free-images.jpg', 'https://www.w3schools.com/', 'https://www.youtube.com/watch?v=CtLdWZ1IV6o');

-- --------------------------------------------------------

--
-- Table structure for table `commentblogpost`
--

CREATE TABLE `commentblogpost` (
  `commentID` int(11) NOT NULL,
  `blogPostID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `commentText` text NOT NULL,
  `LikesOnComment` int(11) NOT NULL,
  `TimeOfComment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commentblogpost`
--

INSERT INTO `commentblogpost` (`commentID`, `blogPostID`, `userID`, `commentText`, `LikesOnComment`, `TimeOfComment`) VALUES
(5, 17, 1, 'i like them', 0, '2024-04-24 02:15:45'),
(6, 15, 1, 'lalalalala', 1, '2024-04-24 23:06:33'),
(7, 15, 1, 'nice', 1, '2024-04-24 23:06:41'),
(8, 22, 22, 'nice post :)', 1, '2024-04-25 03:18:09');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `username` text NOT NULL,
  `firstName` text NOT NULL,
  `lastName` text NOT NULL,
  `email` text NOT NULL,
  `hashedPass` text NOT NULL,
  `securityQuestionAns` text NOT NULL,
  `DOB` text NOT NULL,
  `profilePicture` text NOT NULL DEFAULT 'images/defaultProfilePicture.png',
  `bannerPicture` text NOT NULL DEFAULT 'images/banner.jpg',
  `jobTitle` text NOT NULL DEFAULT 'user',
  `profileLikes` int(11) NOT NULL,
  `Flagged` text NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `username`, `firstName`, `lastName`, `email`, `hashedPass`, `securityQuestionAns`, `DOB`, `profilePicture`, `bannerPicture`, `jobTitle`, `profileLikes`, `Flagged`) VALUES
(1, 'Joe', 'Joe', 'Joe', 'Joe@gmail.com', '$2y$10$LufQlzgdbtteuWlvxr0OQ.FojZeGm4PUGqZZn/u4aqDws1Vt3sWFy', '.', '2024-03-08', 'images/defaultProfilePicture.png', 'images/banner.jpg', 'user', 4, 'no'),
(2, '123Jacob', 'Jacob', 'Heyes', 'heyesjacob@gmail.com', '$2y$10$LufQlzgdbtteuWlvxr0OQ.FojZeGm4PUGqZZn/u4aqDws1Vt3sWFy', '123', '2024-04-10', 'images/defaultProfilePicture.png', 'images/banner.jpg', 'user', 0, 'no'),
(3, 'asd', 'asd', 'asd', 'asd', '$2y$10$LufQlzgdbtteuWlvxr0OQ.FojZeGm4PUGqZZn/u4aqDws1Vt3sWFy', 'asd', '2024-04-10', 'images/defaultProfilePicture.png', 'images/banner.jpg', 'user', 0, ''),
(4, 'as', 'as', 'as', 'as', '$2y$10$LufQlzgdbtteuWlvxr0OQ.FojZeGm4PUGqZZn/u4aqDws1Vt3sWFy', 'as', '2024-04-10', 'images/defaultProfilePicture.png', 'images/banner.jpg', 'user', 0, ''),
(17, 'Admin', 'Admin', 'Admin', 'Admin@gmail.com', '$2y$10$LufQlzgdbtteuWlvxr0OQ.FojZeGm4PUGqZZn/u4aqDws1Vt3sWFy', 'Admin', '2024-04-02', 'images/defaultProfilePicture.png', 'images/banner.jpg', 'Admin', 0, ''),
(18, 'not', 'not', 'not', 'not', '$2y$10$LufQlzgdbtteuWlvxr0OQ.FojZeGm4PUGqZZn/u4aqDws1Vt3sWFy', 'not', '2024-04-02', 'images/defaultProfilePicture.png', 'images/banner.jpg', 'user', 0, ''),
(19, 'asd', 'sad', 'asd', 'asd', '$2y$10$LufQlzgdbtteuWlvxr0OQ.FojZeGm4PUGqZZn/u4aqDws1Vt3sWFy', 'asd', '2024-04-02', 'images/defaultProfilePicture.png', 'images/banner.jpg', 'user', 0, ''),
(20, 'Moderator', 'Moderator', 'Moderator', 'Moderator@gmail.com', '$2y$10$LufQlzgdbtteuWlvxr0OQ.FojZeGm4PUGqZZn/u4aqDws1Vt3sWFy', 'Moderator', '2014-01-01', 'images/defaultProfilePicture.png', 'images/banner.jpg', 'Moderator', 1, ''),
(21, 'Admin2', 'Admin2', 'Admin2', 'admin2@gmail.com', '$2y$10$LufQlzgdbtteuWlvxr0OQ.FojZeGm4PUGqZZn/u4aqDws1Vt3sWFy', 'Admin2', '2002-05-27', 'images/defaultProfilePicture.png', 'images/banner.jpg', 'Admin', 0, ''),
(22, 'Charlie', 'Charlie', 'Charlie', 'Charlie@gmail.com', '$2y$10$LufQlzgdbtteuWlvxr0OQ.FojZeGm4PUGqZZn/u4aqDws1Vt3sWFy', 'Manchester', '2006-09-27', 'https://buffer.com/library/content/images/size/w1200/2023/10/free-images.jpg', 'https://t4.ftcdn.net/jpg/04/95/28/65/360_F_495286577_rpsT2Shmr6g81hOhGXALhxWOfx1vOQBa.jpg', 'user', 1, 'no'),
(23, 'Admin3', 'Admin3', 'Admin3', 'Admin3@gmail.com', '$2y$10$LufQlzgdbtteuWlvxr0OQ.FojZeGm4PUGqZZn/u4aqDws1Vt3sWFy', 'Admin3', '2024-04-25', 'images/defaultProfilePicture.png', 'images/banner.jpg', 'Admin', 0, 'no'),
(24, 'Moderator2', 'Moderator2', 'Moderator2', 'Moderator2@gmail.com', '$2y$10$LufQlzgdbtteuWlvxr0OQ.FojZeGm4PUGqZZn/u4aqDws1Vt3sWFy', 'Moderator2', '2024-04-03', 'images/defaultProfilePicture.png', 'images/banner.jpg', 'Moderator', 0, 'no'),
(25, 'Admins', 'Admins', 'Admins', 'Admins', '$2y$10$LufQlzgdbtteuWlvxr0OQ.FojZeGm4PUGqZZn/u4aqDws1Vt3sWFy', 'Admins', '2024-04-04', 'images/defaultProfilePicture.png', 'images/banner.jpg', 'Admin', 0, 'no');

-- --------------------------------------------------------

--
-- Table structure for table `userlikedcomments`
--

CREATE TABLE `userlikedcomments` (
  `userID` int(11) NOT NULL,
  `CommentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userlikedcomments`
--

INSERT INTO `userlikedcomments` (`userID`, `CommentID`) VALUES
(1, 6),
(1, 7),
(22, 8);

-- --------------------------------------------------------

--
-- Table structure for table `userlikedposts`
--

CREATE TABLE `userlikedposts` (
  `userID` int(11) NOT NULL,
  `blogPostID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userlikedposts`
--

INSERT INTO `userlikedposts` (`userID`, `blogPostID`) VALUES
(17, 16),
(17, 17),
(1, 17),
(22, 22),
(24, 16);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogpost`
--
ALTER TABLE `blogpost`
  ADD PRIMARY KEY (`blogPostID`),
  ADD KEY `user_blogpost` (`userID`);

--
-- Indexes for table `commentblogpost`
--
ALTER TABLE `commentblogpost`
  ADD PRIMARY KEY (`commentID`),
  ADD KEY `comment_blogpost` (`blogPostID`),
  ADD KEY `comment_blogpost_user` (`userID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `userlikedcomments`
--
ALTER TABLE `userlikedcomments`
  ADD KEY `likedcomment_user` (`userID`),
  ADD KEY `likedcomment_comment` (`CommentID`);

--
-- Indexes for table `userlikedposts`
--
ALTER TABLE `userlikedposts`
  ADD KEY `likedpost_user` (`userID`),
  ADD KEY `likedpost_post` (`blogPostID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogpost`
--
ALTER TABLE `blogpost`
  MODIFY `blogPostID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `commentblogpost`
--
ALTER TABLE `commentblogpost`
  MODIFY `commentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blogpost`
--
ALTER TABLE `blogpost`
  ADD CONSTRAINT `user_blogpost` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `commentblogpost`
--
ALTER TABLE `commentblogpost`
  ADD CONSTRAINT `comment_blogpost` FOREIGN KEY (`blogPostID`) REFERENCES `blogpost` (`blogPostID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_blogpost_user` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `userlikedcomments`
--
ALTER TABLE `userlikedcomments`
  ADD CONSTRAINT `likedcomment_comment` FOREIGN KEY (`CommentID`) REFERENCES `commentblogpost` (`commentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likedcomment_user` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `userlikedposts`
--
ALTER TABLE `userlikedposts`
  ADD CONSTRAINT `likedpost_post` FOREIGN KEY (`blogPostID`) REFERENCES `blogpost` (`blogPostID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likedpost_user` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
