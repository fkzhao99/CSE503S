| grades | CREATE TABLE `grades` (
  `pk_grade_ID` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` smallint(255) unsigned NOT NULL,
  `grade` decimal(5,2) NOT NULL,
  `school_code` enum('L','B','A','F','E','T','I','W','S','U','M') NOT NULL,
  `dept_id` tinyint(255) unsigned NOT NULL,
  `course_code` char(5) NOT NULL,
  PRIMARY KEY (`pk_grade_ID`,`student_id`),
  KEY `grades_ibfk_2` (`student_id`),
  KEY `grades_ibfk_1` (`school_code`,`dept_id`,`course_code`),
  CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`school_code`, `dept_id`, `course_code`) REFERENCES `courses` (`school_code`, `dept_id`, `course_code`),
  CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 |

