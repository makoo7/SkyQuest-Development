<?php

return [
    'PER_PAGE_RECORD' => 10,
    'PER_PAGE_REPORT' => 5,
    'PER_PAGE_SEARCH' => 5,
    'PER_PAGE_SEARCHLIST' => 5,

    'DISPLAY_DATE_FORMAT' => 'd/m/Y',
    'DISPLAY_TIME_FORMAT' => 'h:i A',
    'DISPLAY_DATE_TIME_FORMAT' => 'd/m/Y h:i A',
    'DISPLAY_REPORT_DATE' => 'F, Y',
    'DISPLAY_SEARCH_REPORT_DATE' => 'j F, Y',

    'EDITOR_PATH' => "/editor/",
    'ADMIN_PATH' => "/admin/",
    'SERVICE_PATH' => "/service/",
    'USER_PATH' => "/user/",
    'SECTORS_PATH' => "/sectors/",
    'CASESTUDY_PATH' => "/case-study/",
    'AWARD_PATH' => "/award/",
    'INSIGHT_PATH' => "/insight/",
    'CLIENTFEEDBACK_PATH' => "/client-feedback/",
    'OURTEAM_PATH' => "/our-team/",
    'JOBAPPLICATION_PATH' => "/job-application/",
    'REPORT_PATH' => "/report/",
    'GALLERY_PATH' => "/gallery/",
    'POWERED_BY_URL' => env('APP_URL', 'https://skyquestt.com/'),
    'ALLOWED_IMAGE_TYPES' => ["image/jpeg","image/jpg","image/png","image/webp","image/gif","image/bmp"],

    'STRIPE_MODE' => env('STRIPE_MODE', 'live'),
    'STRIPE_PK_TEST' => "pk_test_Wde3WUsMvZXUoJajSj78l1gl",
    'STRIPE_SK_TEST' => "sk_test_DuOEuGw7YAPvhaqxtfq9pMq9",
    'STRIPE_PK_LIVE' => "pk_test_Wde3WUsMvZXUoJajSj78l1gl",
    'STRIPE_SK_LIVE' => "sk_test_DuOEuGw7YAPvhaqxtfq9pMq9",

    'RAZORPAY_MODE' => env('RAZORPAY_MODE','live'),
    'RAZORPAY_KEY_TEST' => env('RAZORPAY_KEY_TEST'),
    'RAZORPAY_SECRET_TEST' => env("RAZORPAY_SECRET_TEST"),
    'RAZORPAY_KEY_LIVE' => env('RAZORPAY_KEY_LIVE'),
    'RAZORPAY_SECRET_LIVE' => env("RAZORPAY_SECRET_LIVE"),

    'PAYPAL_MODE' => env('PAYPAL_MODE','live'),
    'PAYPAL_SANDBOX_CLIENT' => env('PAYPAL_SANDBOX_CLIENT'),
    'PAYPAL_LIVE_CLIENT' => env('PAYPAL_LIVE_CLIENT'),

    'TO_EMAILS' => env('TO_EMAILS','rutvim@moveoapps.in'),
    'CC_EMAILS' => env('CC_EMAILS','rutvim@moveoapps.in'),
    'HR_EMAILS' => env('HR_EMAILS','rutvim@moveoapps.in'),
    'LEADS_EMAILS' => env('LEADS_EMAILS', 'rutvim@moveoapps.in'),
    'ACCOUNT_EMAILS' => env('ACCOUNT_EMAILS', 'rutvim@moveoapps.in'),
    'BUYNOW_EMAILS' => env('BUYNOW_EMAILS', 'rutvim@moveoapps.in'),
    'REPORTEXPORT_EMAILS' => env('REPORTEXPORT_EMAILS', 'rutvim@moveoapps.in'),
    'SUPERADMIN_EMAILS' => env('SUPERADMIN_EMAILS', 'rutvim@moveoapps.in'),

    //'TICKET_API_URL' => env('TICKET_API_URL', 'https://skyquestt.freshdesk.com/api/v2/tickets'),
    'TICKET_API_URL' => env('TICKET_API_URL', 'https://skyquestt-org.myfreshworks.com/crm/sales/api/'),
    'FRESHDESK_API_KEY' => env('FRESHDESK_API_KEY', '_-VCFqAlnhPtzrJVR1I_zQ'),
    'GROUP_ID' => env('GROUP_ID', 84000285248),
    'EMAIL_CONFIG_ID' => env('EMAIL_CONFIG_ID', 84000071727),
    'RESPONDER_ID' => env('RESPONDER_ID', 84015340169),
    'TABLE_OF_CONTENTS_1' => [
        "1. INTRODUCTION" =>
        [
            "1.1 Objectives of the study",
            "1.2 Geographic scope",
            "1.3 Market segmental scope",
            "1.4 Key data points covered",
            "1.5 Definitions"
        ],
        "2. RESEARCH METHODOLOGY" => [
            "2.1 Secondary research",
            "2.2 Primary research",
            "2.3 Primary research approach & key respondents",
            "2.4 Data Triangulation & Insight Generation"
        ],
        "3. EXECUTIVE SUMMARY" => [],
        "4. MARKET OVERVIEW" => [],
        "4.1 INDUSTRY ANALYSIS - PORTERS 5 FORCE ANALYSIS" => [
            "4.2.1 Threat of New Entrants",
            "4.2.2 Bargaining Power of Suppliers",
            "4.2.3 Bargaining Power of Buyers",
            "4.2.4 Threat of Substitute Products or Services",
            "4.2.5 Intensity of Competitive Rivalry",
            "4.1 Market drivers & opportunities",
            "4.2 Market challenges",
            "4.3 Recent Industry Developments"
        ],
        "5. KEY MARKET INSIGHTS" => [
            "5.1 Degree of Competition",
            "5.2 Pricing Analysis",
            "5.3 Market Ecosystem Map"
        ],
        "6. MARKET SIZE, 2021-2030" => []
    ],
    'TABLE_OF_CONTENTS_2' => [
        "6.4.1 North America" => [
            "6.4.1.1 USA" => ["Segment 1", "Segment 2"],
            "6.4.1.2 Canada"
        ],
        "6.4.2 Europe" => [
            "6.4.2.1 UK",
            "6.4.2.2 Germany",
            "6.4.2.3 Spain",
            "6.4.2.4 France",
            "6.4.2.5 Italy",
            "6.4.2.6 Rest of Europe"
        ],
        "6.4.3 Asia-Pacific" => [
            "6.4.3.1 China",
            "7.4.3.2 India",
            "7.4.3.3 Japan",
            "7.4.3.4 South Korea",
            "7.4.3.5 Rest of Asia Pacific"
        ],
        "6.4.4 Middle East & Africa (MEA)" => [
            "6.4.4.1 South Africa",
            "6.4.4.2 GCC Countries",
            "6.4.4.3 Rest of MEA"
        ],
        "6.4.4 Latin America (LATAM)" => [
            "6.4.4.1 Brazil",
            "6.4.4.2 Rest of LATAM"
        ],
        "7. COMPETITIVE INTELLIGENCE" => [
            "7.1 Market Share Analysis by Company",
            "7.2 Intensity of Competitive Rivalry",
            "7.3 Market positioning of key players in the market"
        ],
        "8. COMPANY PROFILES" => [
            "8.1 Top 10 Players"
        ],
        "9. NEED GAP ASSESSMENT" => [
            "9.1 Identified Gaps in the Market",
            "9.2 Opportunities for Innovation"
        ],
        "10. CONCLUSION" => [
            "9.1 Recommendations",
            "9.2 Conclusions"
        ]
        ],
        'OBJECTIVES_OF_THE_STUDY' => [
            "MARKET INTELLIGENCE" => [
                "Determining and projecting the size of the {%%reportname%%} with respect to {%%segmentations%%} region, over ranging from a 10 year period  2020 to 2030",
                "Identifying the attractive opportunities in the market by determining the largest and fastest-growing segments across the key regions",
                "Analyzing the demand-side factors on the basis of the following:" => [
                    "Impact of macro- and micro-economic factors on the market",
                    "Shifts in demand patterns across different subsegments and regions"
                ]
                ],
            "COMPETITIVE INTELLIGENCE" => [
                "Identifying and profiling the key market players in the {%%reportname%%} market",
                "Determining the market share of key players operating in the {%%reportname%%} market",
                "Providing a comparative analysis of the market leaders on the basis of the following:" => [
                    "Product offerings",
                    "Business strategies",
                    "Strengths and weaknesses",
                    "Key financials",
                    "Understanding the competitive landscape and identifying the major growth strategies (mergers, acquisitions, joint ventures) adopted by players across the key regions"
                ],
                "Providing insights on the trade scenario"
            ]
        ],
        "KEY_DATA_POINTS_COVERED_IN_REPORT" => [
            "Detailed Market Size & Growth Estimation for 15+ Countries till 2030",
            "Global and Country Market Trends",
            "Segment trends, opportunity, and growth analysis",
            "Investment and funding trends",
            "Regulatory Landscape Analysis ",
            "Pricing analysis",
            "Comprehensive Mapping Of Industry  Parameters",
            "Company Analysis focusing  on financial performance and competitive Strategies Adopted by Leading Market  Participants",
            "Market drivers, restraints, opportunities  and its impact on the market"
        ]
];
