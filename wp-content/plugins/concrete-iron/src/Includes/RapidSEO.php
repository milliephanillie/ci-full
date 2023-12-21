<?php
namespace ConcreteIron\Includes;

class RapidSEO {
    const PAGE_TEMPLATE = 'concrete-iron-seo';
    const PAGE_IDENTIFIER = 'rapid-seo';
    const SEPARATOR = '|';
    const SITE_TITLE = 'ConcreteIron Classified Ads';

    private $query_params = [
        'concrete-batching-type' => [
            'priority' => 2,
            'values' => [
                'cellular-foam-plants' => [
                    'parent' => 'concrete-batching-equipment',
                    'priority' => 2,
                    'title' => 'Cellular Foam Plants For Sale | ConcreteIron Classified Ads',
                    'meta_desc' => 'Step up your construction game with our concrete batching equipment, featuring the latest cement mixers and cellular foam plants ideal for any project scale. Secure your perfect match today!',
                ],
                'cement-blending-equipment' => [
                    'priority' => 2,
                    'parent' => 'concrete-batching-equipment',
                    'title' => 'Cement Blending Equipment For Sale | Concrete Cement Mixer',
                    'meta_desc' => 'Enhance your concrete production with top-notch batching equipment. Find your ideal mixers and plants for precise mixture formulations. Shop now!',
                ],
                'cement-transport-storage' => [
                    'priority' => 2,
                    'parent' => 'concrete-batching-equipment',
                    'title' => 'Cement Transport Storage For Sale | Cement Storage Silo | ConcreteIron Classified Ads',
                    'meta_desc' => 'Top-Notch Cement Transport & Storage Solutions Available Now! Find Your Perfect Fit for Sale. Get Quality & Durability. Explore Today!',
                ],
                'batch-plants' => [
                    'priority' => 2,
                    'parent' => 'concrete-batching-equipment',
                    'title' => 'Batch Plants For Sale | Concrete Batch Plant Near Me | ConcreteIron Classified Ads',
                    'meta_desc' => 'Discover the Nearest Concrete Batch Plant! Your Solution for Quality Concrete. Find Convenience Nearby. Locate Yours Today!',
                ],
                'portable-cement-silos' => [
                    'priority' => 2,
                    'parent' => 'concrete-batching-equipment',
                    'title' => 'Portable Cement Silos For Sale | Portable Cement Silo | ConcreteIron Classified Ads',
                    'meta_desc' => 'Portable Cement Silos - Your Key to Flexible Storage! Find Sales Now & Elevate Your Construction Game. Grab Yours Today!',
                ],
                'stationary-cement-silos' => [
                    'priority' => 2,
                    'parent' => 'concrete-batching-equipment',
                    'title' => 'Stationary Cement Silos For Sale | Cement Silo Manufacturer',
                    'meta_desc' => 'Sturdy Stationary Cement Silos Available for Sale! Secure Your Storage Solution Now. Explore Durability & Quality Options!',
                ],
                'volumetric-mixers' => [
                    'priority' => 2,
                    'parent' => 'concrete-batching-equipment',
                    'title' => 'Volumetric Mixers For Sale | Volumetric Concrete Mixers | ConcreteIron Classified Ads',
                    'meta_desc' => 'Get Your Hands on Volumetric Mixers for Sale! Tailor-Made Mixing Solutions at Your Fingertips. Explore Now & Elevate Efficiency!',
                ],
                'boom-pumps' => [
                    'priority' => 2,
                    'parent' => 'concrete-batching-equipment',
                    'title' => 'Boom Pumps For Sale | Concrete Boom Pump | ConcreteIron Classified Ads',
                    'meta_desc' => 'Streamline your construction with high-performance concrete pumping equipment. Our selection includes skid-mounted units for precise pumping.',
                ],
                'grout-pumps' => [
                    'priority' => 2,
                    'parent' => 'concrete-batching-equipment',
                    'title' => 'Grout Pumps For Sale | Concrete Grout Pump | ConcreteIron Classified Ads',
                    'meta_desc' => 'Powerful Concrete Grout Pump Solutions! Upgrade Your Projects with Precision. Explore Top-Quality Pumps Now.',
                ],
                'line-pumps' => [
                    'priority' => 2,
                    'parent' => 'concrete-batching-equipment',
                    'title' => 'Line Pumps For Sale | Concrete Line Pump | ConcreteIron Classified Ads',
                    'meta_desc' => 'Maximize efficiency with advanced concrete pumping equipment. Tailor your search for trailer to boom pumps to meet your project needs. Shop now.',
                ],
                'placing-booms-and-accessories' => [
                    'priority' => 2,
                    'parent' => 'concrete-batching-equipment',
                    'title' => 'Placing Booms and Accessories For Sale | Concrete Placing Boom | ConcreteIron Classified Ads',
                    'meta_desc' => 'Upgrade Your Construction Game! Placing Booms & Accessories for Sale. Elevate Efficiency & Precision Today!',
                ],
                'plaster-pumps' => [
                    'priority' => 2,
                    'parent' => 'concrete-batching-equipment',
                    'title' => 'Plaster Pumps For Sale | Plaster Pumps and Equipment | ConcreteIron Classified Ads',
                    'meta_desc' => 'Precision & Efficiency in Plastering! Top-Notch Pumps & Equipment for Sale. Explore High-Quality Options Now!',
                ],
                '3d-profiling-systems' => [
                    'priority' => 2,
                    'parent' => 'concrete-placing-and-finishing-equipment',
                    'title' => '3D Profiling Systems For Sale | ConcreteIron Classified Ads',
                    'meta_desc' => 'Your project requires top-quality concrete equipment, and we\'ve got it all. From mixing to demolition, browse our full range to find the tools.',
                ],
                'belt-trucks-or-telebelts' => [
                    'priority' => 2,
                    'parent' => 'concrete-placing-and-finishing-equipment',
                    'title' => 'Truck Belt | Telebelt for Sale | ConcreteIron Classified Ads',
                    'meta_desc' => 'Effortless Truck Belts for Efficiency! Discover Reliable Solutions for Your Operations. Explore Now for Seamless Performance!',
                ],
                'concrete-buckets' => [
                    'priority' => 2,
                    'parent' => 'concrete-placing-and-finishing-equipment',
                    'title' => 'Concrete Buckets For Sale | Concrete Mixer Bucket For Sale | ConcreteIron Classified Ads',
                    'meta_desc' => 'Upgrade Your Concrete Workflow! Quality Buckets for Sale. Explore Efficient Solutions Today. Choose from a variety of mixers, pumps.',
                ],
                'concrete-buggies' => [
                    'priority' => 2,
                    'parent' => 'concrete-placing-and-finishing-equipment',
                    'title' => 'Concrete Buggies For Sale | Concrete Buggies For Sale | ConcreteIron Classified Ads',
                    'meta_desc' => 'Smooth Moves with Concrete Buggies for Sale! Explore Efficiency & Ease. Upgrade Your Workflow Today!',
                ],
                'concrete-finishing-tools' => [
                    'priority' => 2,
                    'parent' => 'concrete-placing-and-finishing-equipment',
                    'title' => 'Concrete Finishing Tools For Sale | Cement Finishing Tools | ConcreteIron Classified Ads',
                    'meta_desc' => 'Revamp Your Projects! Top-Quality Concrete Finishing Tools for Sale - Grab Yours Now & Master the Art! #1 Choice for Pro Finishers.',
                ],
                'concrete-vibrators' => [
                    'priority' => 2,
                    'parent' => 'concrete-placing-and-finishing-equipment',
                    'title' => 'Concrete Vibrators For Sale | Concrete Vibration Tool | ConcreteIron Classified Ads',
                    'meta_desc' => 'Get Projects Rocking! Concrete Vibrators for Sale - Power Up Your Work Effortlessly. Find Yours Today!',
                ],
                'laser-screeds' => [
                    'priority' => 2,
                    'parent' => 'concrete-placing-and-finishing-equipment',
                    'title' => 'Laser Screeds For Sale | Concrete Laser Screeds For Sale | ConcreteIron Classified Ads',
                    'meta_desc' => 'Precision Redefined! Laser Screeds for Sale - Level Up Your Projects with Accuracy. Find Yours Now!',
                ],
                'line-handling-equipment' => [
                    'priority' => 2,
                    'parent' => 'concrete-placing-and-finishing-equipment',
                    'title' => 'Line Handling Equipment For Sale | Material Handling Equipment | ConcreteIron Classified Ads',
                    'meta_desc' => 'Seize Control! Line Handling Equipment for Sale - Elevate Efficiency & Precision. Get Yours Today!',
                ],
                'portable-conveyors' => [
                    'priority' => 2,
                    'parent' => 'concrete-placing-and-finishing-equipment',
                    'title' => 'Portable Conveyors For Sale | Portable Conveyor Belt | ConcreteIron Classified Ads',
                    'meta_desc' => 'For every concrete task, there\'s a tool to match in our extensive equipment category. Choose from a variety of pumps, mixers, and finishing tools.',
                ],
                'roller-screeds' => [
                    'priority' => 2,
                    'parent' => 'concrete-placing-and-finishing-equipment',
                    'title' => 'Roller Screeds For Sale | Concrete Roller Screeds | ConcreteIron Classified Ads',
                    'meta_desc' => 'Smooth Moves Await! Roller Screeds for Sale - Effortless Finishing for Perfect Results. Grab Yours Now!',
                ],
                'pavers-and-curb-machines' => [
                    'priority' => 2,
                    'parent' => 'concrete-placing-and-finishing-equipment',
                    'title' => 'Pavers and Curb Machines For Sale | Concrete Curb Machine',
                    'meta_desc' => 'Craft Excellence! Pavers and Curb Machines for Sale - Build Dreams, One Block at a Time. Find Yours Now!',
                ],
                'truss-screens' => [
                    'priority' => 2,
                    'parent' => 'concrete-placing-and-finishing-equipment',
                    'title' => 'Buy or Sell Concrete Placing and Finishing Equipment | ConcreteIron Classified Ads',
                    'meta_desc' => 'Upgrade Your Concrete Game! Buy or Sell Placing & Finishing Equipment - Ace Every Project. Explore Now!',
                ],
                'walk-behind-power-tools' => [
                    'priority' => 2,
                    'parent' => 'concrete-placing-and-finishing-equipment',
                    'title' => 'Walk-Behind Concrete Power Trowels | ConcreteIron Classified Ads',
                    'meta_desc' => 'Step Up Your Finish! Walk-Behind Concrete Power Trowels - Smooth, Swift, and Efficient. Explore Now!',
                ],
                // Start of 'concrete-cutting-and-demolition-equipment' category
                'concrete-breakers' => [
                    'priority' => 2,
                    'parent' => 'concrete-cutting-and-demolition-equipment',
                    'title' => 'Concrete Breakers For Sale | Concrete Breakers Near Me | ConcreteIron Classified Ads',
                    'meta_desc' => 'Discover durable concrete breakers for sale at unbeatable prices. Upgrade your equipment with top-quality options today. Find the perfect match!',
                ],
                'concrete-drills-core-drills' => [
                    'priority' => 2,
                    'parent' => 'concrete-cutting-and-demolition-equipment',
                    'title' => 'Concrete Drills For Sale | Concrete Core Drill  | Concrete Core Drill Bit',
                    'meta_desc' => 'Explore top-notch concrete drills for sale at unbeatable prices. Upgrade your toolkit today with our quality options!',
                ],
                'concrete-saws' => [
                    'priority' => 2,
                    'parent' => 'concrete-cutting-and-demolition-equipment',
                    'title' => 'Concrete Saws For Sale | Concrete Saw Cutter | Electric Concrete Saw',
                    'meta_desc' => 'Browse through durable concrete saws for sale at great prices. Enhance your tools with our top-quality options!',
                ],
                'hydro-demolition' => [
                    'priority' => 2,
                    'parent' => 'concrete-cutting-and-demolition-equipment',
                    'title' => 'Hydro Demolition For Sale | Hydro Demolition Equipment | Hydro Demolition Concrete',
                    'meta_desc' => 'Explore powerful hydro demolition equipment for sale at competitive prices. Elevate your demolition game with top-notch options!',
                ],
                'jack-hammers' => [
                    'priority' => 2,
                    'parent' => 'concrete-cutting-and-demolition-equipment',
                    'title' => 'Jack Hammers For Sale | Electric Jack Hammers for Sale | ConcreteIron Classified Ads',
                    'meta_desc' => 'Find high-performance electric jack hammers for sale at competitive prices. Upgrade your tools effortlessly with top-quality options!',
                ],
                'precast-plants' => [
                    'priority' => 2,
                    'parent' => 'concrete-precast-equipment',
                    'title' => 'Precast Plants For Sale | Precast Concrete Plant | Precast Batch Plant',
                    'meta_desc' => 'Explore advanced precast concrete plant solutions for streamlined production. Elevate efficiency and quality with our tailored options!',
                ],
                'precast-molds' => [
                    'priority' => 2,
                    'parent' => 'concrete-precast-equipment',
                    'title' => 'Precast Molds For Sale | Precast Concrete Molds for Sale',
                    'meta_desc' => 'Find high-quality precast concrete molds for sale at great prices. Upgrade your production with our durable and versatile options!',
                ],
                'other-precast-equipment' => [
                    'priority' => 2,
                    'parent' => 'concrete-precast-equipment',
                    'title' => 'Other Precast Equipment For Sale | Precast Concrete Plant Equipment',
                    'meta_desc' => 'Discover a range of reliable precast equipment for sale at competitive prices. Elevate your production with top-notch options!',
                ],
                'precast-batching' => [
                    'priority' => 2,
                    'parent' => 'concrete-precast-equipment',
                    'title' => 'Precast Batching For Sale | Precast Concrete Batch Plant | ConcreteIron Classified Ads',
                    'meta_desc' => 'Explore efficient precast concrete batch plant solutions for increased productivity and quality output. Find tailored options for your needs!',
                ],
                // Start of '3d-concrete-printing-equipment' category
                '3d-printer-concrete-pumps' => [
                    'priority' => 2,
                    'parent' => '3d-concrete-printing-equipment',
                    'title' => 'Concrete Pump For 3D Printer | ConcreteIron Classified Ads',
                    'meta_desc' => 'Discover an innovative concrete pump designed for 3D printers. Enhance precision and efficiency in your printing process with this quality pump!',
                ],
                'gantry-3d-printers' => [
                    'priority' => 2,
                    'parent' => '3d-concrete-printing-equipment',
                    'title' => '3d Printers For Sale| Industrial 3d Printers | ConcreteIron Classified Ads',
                    'meta_desc' => 'Explore a range of quality 3D printers for sale at competitive prices. Find the ideal model for your printing needs today!',
                ],
                'robotic-arm-concrete-3d-printers' => [
                    'priority' => 2,
                    'parent' => '3d-concrete-printing-equipment',
                    'title' => 'Robotic Arm Concrete 3D Printers For Sale | 3D Concrete Printing Equipment',
                    'meta_desc' => 'Explore advanced robotic arm concrete 3D printers for sale. Achieve precision and efficiency in concrete printing with these top-notch printers!',
                ],
                //other-concrete-equipment
                'concrete-engraving' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Concrete Engraving For Sale | Concrete Engraving Machine | Concrete Engravers',
                    'meta_desc' => 'Complete your concrete equipment collection with our specialized selections. Dive into a variety of quality tools designed for specific concrete applications.',
                ],
                'concrete-stamp-tools' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Concrete Stamp Tools For Sale | Stamped Concrete Near Me | ConcreteIron Classified Ads',
                    'meta_desc' => 'Discover high-quality concrete stamp tools for sale at competitive prices. Elevate your detailing game with these precision tools!',
                ],
                'concrete-forms' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Concrete Forms For Sale | Insulated Concrete Forms | Concrete Wall Forms',
                    'meta_desc' => 'Find durable and versatile concrete forms for sale at great prices. Enhance precision and quality in your projects with these reliable forms!',
                ],
                'other-concrete-related-equipment' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Other Concrete Related Equipment For Sale | Tools and Equipment Concrete Contractors',
                    'meta_desc' => 'Explore a range of high-quality tools and equipment for concrete contractors. Elevate your projects with our reliable and efficient options!',
                ],
                'other-gunite-equipment' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Gunite Equipment For Sale | Gunite Supply & Equipment | ConcreteIron Classified Ads',
                    'meta_desc' => 'Find top-quality gunite equipment for sale at competitive prices. Enhance your spraying projects with our reliable and efficient options!',
                ],
                'rebar-equipment' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Rebar Equipment for Sale | Rebar Bending Equipment',
                    'meta_desc' => 'Discover premium rebar equipment for sale at competitive prices. Elevate your construction projects with durable and efficient options!',
                ],
                'air-compressors' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Air Compressors For Sale | Portable Air Compressor | ConcreteIron Classified Ads',
                    'meta_desc' => 'Explore a variety of quality air compressors for sale at competitive prices. Get the right one for your needs and projects!',
                ],
                'backhoes' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Backhoes For Sale | Backhoes For Sale Near Me | Small Backhoes For Sale',
                    'meta_desc' => 'Discover reliable backhoes for sale near you at competitive prices. Find the perfect fit for your projects!',
                ],
                'compact-track-loaders' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Compact Track Loaders For Sale | Compact Track Loaders For Sale Near Me',
                    'meta_desc' => 'Discover reliable compact track loaders for sale at competitive prices. Choose the right one for your projects!',
                ],
                'concrete-heaters' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Concrete Heaters For Sale | Concrete Ground Heaters | ConcreteIron Classified Ads',
                    'meta_desc' => 'Find reliable concrete heaters for sale at competitive prices. Enhance efficiency in your projects with these quality heating options!',
                ],
                'concrete-reclaimers' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Concrete Reclaimers For Sale | Concrete Reclaimer System | ConcreteIron Classified Ads',
                    'meta_desc' => 'Discover efficient concrete reclaimers for sale at competitive prices. Reuse materials and enhance sustainability in your projects!',
                ],
                'concrete-washout' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Concrete Washout For Sale | Concrete Washout Dumpster | ConcreteIron Classified Ads',
                    'meta_desc' => 'Find efficient concrete washout solutions for sale at competitive prices. Keep your site clean and environmentally friendly!',
                ],
                'cranes' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Cranes For Sale | Crane Truck For Sale | Service Truck Cranes For Sale',
                    'meta_desc' => 'Discover reliable crane trucks for sale at competitive prices. Elevate your projects with these efficient lifting solutions!',
                ],
                'dozers' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Dozers For Sale | Dozers For Sale Near Me | Small Dozers For Sale',
                    'meta_desc' => 'Discover dozers for sale near your location at competitive prices. Get the right equipment for your projects!',
                ],
                'dust-control' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Dust Control For Sale | Construction Dust Control | Dust Control Near Me',
                    'meta_desc' => 'Find efficient dust control solutions for sale at competitive prices. Keep your site clean and safe with these effective options!',
                ],
                'excavators' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Excavators For Sale | Mini Excavators For Sale | Excavator For Sale Near Me',
                    'meta_desc' => 'Explore a range of quality excavators for sale at competitive prices. Get the perfect one for your projects!',
                ],
                'forklifts-and-telehandlers' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Forklifts and Telehandlers For Sale | Telehandler For Sale Near Me',
                    'meta_desc' => 'Discover forklifts and telehandlers for sale at competitive prices. Upgrade your equipment for efficient handling!',
                ],
                'fuel-storage' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Fuel Storage For Sale | Fuel Storage Tanks For Sale',
                    'meta_desc' => 'Find durable fuel storage tanks for sale at competitive prices. Securely store fuel with these reliable tanks!',
                ],
                'generators' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Generators For Sale | Generators For Sale Near Me',
                    'meta_desc' => 'Discover reliable generators for sale at competitive prices. Ensure uninterrupted power supply with our quality options!',
                ],
                'ground-heaters' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Ground Heaters For Sale | Electric Pool Heaters Above Ground',
                    'meta_desc' => 'Find efficient ground heaters for sale at competitive prices. Keep your projects on track with reliable heating options!',
                ],
                'light-towers' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Light Towers For Sale | Portable Light Towers | ConcreteIron Classified Ads',
                    'meta_desc' => 'Discover light towers for sale at competitive prices. Illuminate your work areas with these reliable lighting solutions!',
                ],
                'man-lifts-or-work-platforms' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Man Lifts or Work Platforms For Sale | Towable Man Lift For Sale',
                    'meta_desc' => 'Discover man lifts or work platforms for sale at competitive prices. Reach elevated areas with ease using these reliable options!',
                ],
                'pier-drilling-or-foundation-drilling' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Pier Drilling or Foundation Drilling For Sale | Foundation Drilling Companies',
                    'meta_desc' => 'Find pier and foundation drilling for sale at competitive prices. Build solid foundations with these reliable drilling options!',
                ],
                'pressure-washing' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Pressure Washing For Sale | Foundation Drilling Companies Near Me',
                    'meta_desc' => 'Discover efficient pressure washing for sale at competitive prices. Clean with power using these reliable options!',
                ],
                'rebar-machines' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Rebar Machines For Sale | Rebar Bending Machine',
                    'meta_desc' => 'Find quality rebar machines for sale at competitive prices. Reinforce structures effortlessly with these reliable machines!',
                ],
                'scaffolding' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Scaffolding For Sale | Scaffolding For Sale Near Me',
                    'meta_desc' => 'Find durable scaffolding for sale at competitive prices. Secure elevated work platforms with these reliable options!',
                ],
                'skid-steer-attachments' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Skid Steer Attachments For Sale | Skid Steer Attachments Near Me',
                    'meta_desc' => 'Find versatile skid steer attachments for sale at competitive prices. Enhance your equipment with these quality options!',
                ],
                'skid-steer-loaders' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Skid Steer Loaders For Sale | Skid Steer Loaders For Sale Near Me',
                    'meta_desc' => 'Discover skid steer loaders for sale at competitive prices. Get versatile and efficient equipment for your projects!',
                ],
                'stone-slingers' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Stone Slingers For Sale | Stone Slingers Near Me | ConcreteIron Classified Ads',
                    'meta_desc' => 'Find efficient stone slingers for sale at competitive prices. Handle materials with ease using these quality options!',
                ],
                'sweepers-and-brooms' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Sweepers and Brooms For Sale | Sweepers For Sale | ConcreteIron Classified Ads',
                    'meta_desc' => 'Discover sweepers and brooms for sale at competitive prices. Keep your space clean with these efficient tools!',
                ],
                'trailers' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Trailers For Sale | Trailers For Sale Near Me | ConcreteIron Classified Ads',
                    'meta_desc' => 'Find quality trailers for sale at competitive prices. Haul your goods with confidence using these reliable options!',
                ],
                'trenchers' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Trenchers For Sale | Trenchers For Sale Near Me | Small Trenchers',
                    'meta_desc' => 'Find quality trenchers for sale at competitive prices. Dig efficiently with these reliable options!',
                ],
                'trucks' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Trucks For Sale | Construction Trucks For Sale | ConcreteIron Classified Ads',
                    'meta_desc' => 'Discover construction trucks for sale at competitive prices. Get heavy-duty vehicles for your projects!',
                ],
                'vacuums-or-dust-extractors' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Vacuums or Dust Extractors For Sale | Industrial Dust Extractor For Sale',
                    'meta_desc' => 'Find quality vacuums or dust extractors for sale at competitive prices. Clean efficiently with these reliable options!',
                ],
                'welding-equipment' => [
                    'priority' => 2,
                    'parent' => 'other-concrete-equipment',
                    'title' => 'Welding Equipment For Sale | Welding Tools And Equipments',
                    'meta_desc' => 'Find quality welding equipment for sale at competitive prices. Perfect your welds with these reliable tools!',
                ],

            ],
        ]
    ];

    public function __construct() {
        $this->boot();
    }

    public function boot() {
        add_action('wpseo_title', [$this, 'lisfintiy_query_param_title_tags']);
        add_action('wpseo_metadesc', [$this, 'lisfintiy_query_param_meta_desc']);
    }

    public function lisfintiy_query_param_title_tags($title) {
        foreach($this->query_params as $query_param) {
            foreach ($query_param['values'] as $term => $data) {
                if(strpos($_SERVER['QUERY_STRING'], $term) !== false) {
                    $title = $data['title'];
                }
            }
        }

        return $title;
    }

    public function lisfintiy_query_param_meta_desc($meta_desc) {
        foreach($this->query_params as $query_param) {
            foreach ($query_param['values'] as $term => $data) {
                if(strpos($_SERVER['QUERY_STRING'], $term) !== false) {
                    $meta_desc = $data['meta_desc'];
                }
            }
        }

        return $meta_desc;
    }
}