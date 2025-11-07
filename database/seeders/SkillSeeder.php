<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SkillSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        
        // Get department IDs dynamically based on short names
        $departments = [
            'BSARCH' => [
                'Architectural Design',
                'Building Information Modeling (BIM)',
                'Sustainable Design',
                'Urban Planning',
                'Construction Documentation',
                '3D Modeling',
                'Architectural Visualization',
                'Building Codes',
                'Site Planning',
                'Interior Architecture',
                'Structural Systems',
                'Environmental Systems',
                'Historic Preservation',
                'Project Management',
                'Building Materials'
            ],
            'BSID' => [
                'Interior Design',
                'Space Planning',
                'Color Theory',
                'Furniture Design',
                'Lighting Design',
                'Materials and Finishes',
                'CAD for Interiors',
                'Building Codes',
                'Sustainable Design',
                'Residential Design',
                'Commercial Design',
                'Kitchen and Bath Design',
                'Feng Shui',
                'Universal Design',
                'Project Management'
            ],
            'BAEL' => [
                'Creative Writing',
                'Technical Writing',
                'Literary Analysis',
                'Linguistics',
                'Grammar and Syntax',
                'Research Writing',
                'Editing and Proofreading',
                'Public Speaking',
                'Communication Skills',
                'Critical Thinking',
                'Teaching English',
                'Translation',
                'Content Writing',
                'Journalism',
                'Digital Communication'
            ],
            'BSECON' => [
                'Economic Analysis',
                'Statistical Analysis',
                'Microeconomics',
                'Macroeconomics',
                'Econometrics',
                'Financial Markets',
                'Development Economics',
                'International Trade',
                'Public Policy',
                'Cost-Benefit Analysis',
                'Economic Forecasting',
                'Research Methods',
                'Data Analysis',
                'Economic Modeling',
                'Behavioral Economics'
            ],
            'BSES' => [
                'Environmental Assessment',
                'Climate Science',
                'Conservation Biology',
                'Environmental Policy',
                'Sustainability',
                'Ecology',
                'Geographic Information Systems (GIS)',
                'Environmental Chemistry',
                'Waste Management',
                'Water Resources',
                'Air Quality',
                'Environmental Law',
                'Renewable Energy',
                'Environmental Education',
                'Research Methods'
            ],
            'BSCHEM' => [
                'Analytical Chemistry',
                'Organic Chemistry',
                'Inorganic Chemistry',
                'Physical Chemistry',
                'Biochemistry',
                'Laboratory Techniques',
                'Chemical Analysis',
                'Chromatography',
                'Spectroscopy',
                'Chemical Synthesis',
                'Quality Control',
                'Research Methods',
                'Chemical Safety',
                'Instrumentation',
                'Data Analysis'
            ],
            'BSMATH' => [
                'Calculus',
                'Linear Algebra',
                'Differential Equations',
                'Statistics',
                'Probability',
                'Numerical Analysis',
                'Mathematical Modeling',
                'Discrete Mathematics',
                'Abstract Algebra',
                'Real Analysis',
                'Complex Analysis',
                'Operations Research',
                'Financial Mathematics',
                'Teaching Mathematics',
                'Research Methods'
            ],
            'BSSTAT' => [
                'Statistical Analysis',
                'Probability Theory',
                'Regression Analysis',
                'Data Mining',
                'Statistical Computing',
                'Experimental Design',
                'Multivariate Analysis',
                'Time Series Analysis',
                'Bayesian Statistics',
                'Statistical Modeling',
                'Data Visualization',
                'Quality Control',
                'Survey Methodology',
                'Biostatistics',
                'Research Methods'
            ],
            'BSFIL' => [
                'Filipino Literature',
                'Creative Writing in Filipino',
                'Philippine Culture',
                'Linguistics',
                'Translation',
                'Teaching Filipino',
                'Research Writing',
                'Critical Analysis',
                'Public Speaking',
                'Communication Skills',
                'Philippine History',
                'Folklore Studies',
                'Media in Filipino',
                'Technical Writing',
                'Cultural Studies'
            ],
            'BSA' => [
                'Financial Accounting',
                'Managerial Accounting',
                'Auditing',
                'Taxation',
                'Cost Accounting',
                'Financial Analysis',
                'Accounting Software',
                'Internal Controls',
                'Financial Reporting',
                'Forensic Accounting',
                'Budgeting',
                'Accounting Ethics',
                'Business Law',
                'Risk Management',
                'Financial Management'
            ],
            'BSBA-MKT' => [
                'Marketing Strategy',
                'Digital Marketing',
                'Market Research',
                'Consumer Behavior',
                'Brand Management',
                'Advertising',
                'Sales Management',
                'Social Media Marketing',
                'Content Marketing',
                'Marketing Analytics',
                'Product Management',
                'Public Relations',
                'E-commerce',
                'Marketing Communications',
                'Strategic Planning'
            ],
            'BSENTREP' => [
                'Business Planning',
                'Startup Development',
                'Financial Management',
                'Market Analysis',
                'Venture Capital',
                'Innovation Management',
                'Leadership',
                'Negotiation Skills',
                'Risk Management',
                'Business Modeling',
                'Product Development',
                'Sales and Marketing',
                'Networking',
                'Strategic Planning',
                'Funding Strategies'
            ],
            'BSOA' => [
                'Office Management',
                'Records Management',
                'Business Communication',
                'Administrative Support',
                'Office Technology',
                'Meeting Planning',
                'Document Processing',
                'Customer Service',
                'Time Management',
                'Project Coordination',
                'Human Resources',
                'Financial Records',
                'Office Software',
                'Executive Support',
                'Workplace Organization'
            ],
            'BSCE' => [
                'Structural Design',
                'AutoCAD',
                'Revit',
                'Construction Management',
                'Surveying',
                'Geotechnical Engineering',
                'Transportation Engineering',
                'Environmental Engineering',
                'Hydraulics',
                'Project Estimation',
                'Building Codes',
                'Concrete Technology',
                'Steel Design',
                'Road Design',
                'Urban Planning'
            ],
            'BSCHE' => [
                'Process Design',
                'Chemical Reactors',
                'Process Control',
                'Heat Transfer',
                'Mass Transfer',
                'Fluid Mechanics',
                'Plant Design',
                'Safety Engineering',
                'Environmental Engineering',
                'Biochemical Engineering',
                'Petroleum Engineering',
                'Polymer Science',
                'Instrumentation',
                'Quality Control',
                'Research Methods'
            ],
            'BSECE' => [
                'Circuit Design',
                'Digital Electronics',
                'Microelectronics',
                'Communications Systems',
                'Signal Processing',
                'Embedded Systems',
                'VLSI Design',
                'Control Systems',
                'Power Electronics',
                'Electromagnetic Theory',
                'Electronic Devices',
                'Telecommunications',
                'Robotics',
                'Instrumentation',
                'PCB Design'
            ],
            'BSEE' => [
                'Power Systems',
                'Electrical Machines',
                'Control Systems',
                'Renewable Energy',
                'Power Electronics',
                'Electrical Safety',
                'HVAC Systems',
                'Smart Grid Technology',
                'PLC Programming',
                'Electrical Design',
                'Energy Management',
                'Lightning Protection',
                'Motor Control',
                'Electrical Codes',
                'Project Management'
            ],
            'BSGE' => [
                'Land Surveying',
                'Geodetic Engineering',
                'GPS Technology',
                'Cartography',
                'Remote Sensing',
                'Geographic Information Systems (GIS)',
                'Cadastral Survey',
                'Topographic Mapping',
                'Photogrammetry',
                'Boundary Determination',
                'Construction Survey',
                'Hydrographic Survey',
                'Geodetic Computations',
                'Survey Law',
                'Data Analysis'
            ],
            'BSIE' => [
                'Operations Research',
                'Quality Control',
                'Production Planning',
                'Supply Chain Management',
                'Ergonomics',
                'Facilities Planning',
                'Work Measurement',
                'Process Improvement',
                'Project Management',
                'Statistical Analysis',
                'Lean Manufacturing',
                'Six Sigma',
                'Systems Engineering',
                'Human Factors',
                'Cost Analysis'
            ],
            'BSIT' => [
                'Web Development',
                'Frontend Development',
                'Backend Development',
                'Mobile App Development',
                'UI/UX Design',
                'Database Management',
                'Network Administration',
                'Cybersecurity',
                'Cloud Computing',
                'Data Science',
                'Software Testing',
                'DevOps',
                'Programming',
                'System Analysis',
                'IT Project Management'
            ],
            'BSME' => [
                'Machine Design',
                'Thermodynamics',
                'Fluid Mechanics',
                'Heat Transfer',
                'Manufacturing Processes',
                'CAD/CAM',
                'Mechatronics',
                'Robotics',
                'Automotive Engineering',
                'HVAC Systems',
                'Material Science',
                'Vibration Analysis',
                'Control Systems',
                'Energy Systems',
                'Project Management'
            ]
        ];

        foreach ($departments as $shortName => $departmentSkills) {
            // Get the department ID
            $dept = DB::table('departments')->where('short_name', $shortName)->first();
            
            if ($dept) {
                foreach ($departmentSkills as $skillName) {
                    DB::table('skills')->updateOrInsert(
                        ['dept_id' => $dept->dept_id, 'name' => $skillName],
                        ['created_at' => $now, 'updated_at' => $now]
                    );
                }
                $this->command->info("Created skills for {$shortName}");
            } else {
                $this->command->warn("Department {$shortName} not found!");
            }
        }

        $this->command->info('All skills created successfully!');
    }
}