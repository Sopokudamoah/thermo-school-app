import {driver} from "driver.js";
import "driver.js/dist/driver.css";

document.addEventListener('DOMContentLoaded', () => {
    const tourSteps = [
        {
            popover: {
                title: 'Welcome to the Tour!',
                description: 'Let us show you around the main modules and some powerful sub-features of the application.',
                side: "bottom",
                align: 'start'
            }
        }
    ];

    const availableSteps = [
        {id: 'tour-hamburger', title: 'Menu Toggle', desc: 'Use this to expand or collapse the sidebar menu.'},
        {
            id: 'tour-session-banner',
            title: 'Academic Session',
            desc: 'This shows the currently active academic session and semester.'
        },
        {
            id: 'tour-user-dropdown',
            title: 'User Profile',
            desc: 'Access your account settings, change password, or logout here.'
        },

        {
            id: 'tour-dashboard',
            title: 'Dashboard',
            desc: 'Your central hub. Get a quick overview of school activities and important numbers at a glance.'
        },

        {
            id: 'tour-stats',
            title: 'Quick Stats',
            desc: 'See total counts of students, teachers, and classes in the current session.'
        },
        {id: 'tour-gender', title: 'Diversity Tracking', desc: 'Monitor the gender distribution of your student body.'},
        {
            id: 'tour-events-preview',
            title: 'Upcoming Events',
            desc: 'A quick look at your school calendar and upcoming activities.'
        },
        {
            id: 'tour-notices-preview',
            title: 'Recent Notices',
            desc: 'Stay updated with the latest announcements from the administration.'
        },

        {
            id: 'tour-classes',
            title: 'Classes',
            desc: 'Manage your school classes here. You can organize students into different levels and groups.'
        },

        {id: 'tour-my-courses', title: 'My Courses', desc: 'View and manage the specific courses you are teaching.'},
        {id: 'tour-student-attendance', title: 'Attendance', desc: 'Track your daily presence and attendance history.'},
        {
            id: 'tour-student-courses',
            title: 'My Courses',
            desc: 'Access your enrolled courses, study materials, and assignments.'
        },

        {id: 'tour-students', title: 'Students Module', desc: 'Manage everything about your students here.'},
        {
            id: 'tour-student-list',
            title: 'Student List',
            desc: 'Search, filter, and view detailed profiles of all enrolled students.'
        },
        {
            id: 'tour-student-create',
            title: 'Admission',
            desc: 'Easily register new students into the system with our streamlined admission form.'
        },

        {
            id: 'tour-teachers',
            title: 'Teachers Module',
            desc: 'Keep track of your teaching staff and their assignments.'
        },
        {
            id: 'tour-teacher-list',
            title: 'Teacher Directory',
            desc: 'View all teachers, their contact info, and the subjects they teach.'
        },
        {
            id: 'tour-teacher-create',
            title: 'Add Teacher',
            desc: 'Register new faculty members and set up their access accounts.'
        },

        {id: 'tour-exams', title: 'Exams & Grading', desc: 'The core of academic assessment.'},
        {
            id: 'tour-exam-list',
            title: 'Exam Management',
            desc: 'Schedule exams, set dates, and manage different assessment types.'
        },
        {
            id: 'tour-exam-create',
            title: 'Create Exam',
            desc: 'Set up new examination sessions for specific classes and subjects.'
        },
        {
            id: 'tour-exam-marks',
            title: 'Grading',
            desc: 'Input student marks and let the system calculate grades based on your rules.'
        },

        {
            id: 'tour-notice',
            title: 'Notice Board',
            desc: 'Post important announcements and updates for students and staff to see.'
        },
        {
            id: 'tour-events',
            title: 'Events Calendar',
            desc: 'Schedule school events and keep everyone informed about upcoming activities.'
        },
        {
            id: 'tour-syllabus',
            title: 'Syllabus',
            desc: 'Manage course outlines and study materials for different classes.'
        },
        {
            id: 'tour-routine',
            title: 'Class Routine',
            desc: 'Set up and view weekly schedules for classes and teachers.'
        },

        {
            id: 'tour-settings',
            title: 'System Settings',
            desc: 'Control how the application works, including school sessions and academic years.'
        },
        {
            id: 'tour-roles',
            title: 'Roles & Permissions',
            desc: 'Manage what different users can see and do within the system.'
        },
        {
            id: 'tour-promotions',
            title: 'Promotions',
            desc: 'Handle the process of moving students to the next class at the end of the year.'
        },

        {id: 'tour-finance', title: 'Finance Overview', desc: 'Track the financial health of your institution.'},
        {
            id: 'tour-finance-fees',
            title: 'Fee Management',
            desc: 'Define fee structures, track payments, and send reminders.'
        },
        {
            id: 'tour-finance-billing',
            title: 'Billing & Payments',
            desc: 'Manage invoices, receipts, and various payment methods.'
        },
        {
            id: 'tour-finance-expenses',
            title: 'Expense Tracking',
            desc: 'Record school expenditures and manage vendors.'
        },
        {
            id: 'tour-finance-reports',
            title: 'Financial Reports',
            desc: 'Generate detailed reports on fees, revenue, and expenses.'
        },
    ];

    availableSteps.forEach(step => {
        const element = document.getElementById(step.id);
        if (element) {
            tourSteps.push({
                element: `#${step.id}`,
                popover: {
                    title: step.title,
                    description: step.desc,
                    side: "right",
                    align: 'start'
                }
            });
        }
    });

    tourSteps.push({
        popover: {
            title: 'Tour Complete!',
            description: 'You are all set! Explore the modules to see more details. You can restart this tour anytime by clicking the Tour button.',
            side: "bottom",
            align: 'start'
        }
    });

    const driverObj = driver({
        showProgress: true,
        animate: true,
        allowClose: true,
        opacity: 0.7,
        stagePadding: 5,
        steps: tourSteps
    });

    const startBtn = document.getElementById('start-tour');
    if (startBtn) {
        startBtn.addEventListener('click', (e) => {
            e.preventDefault();
            driverObj.drive();
        });
    }
});
