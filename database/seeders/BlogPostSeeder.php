<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\BlogCategory;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'category_id' => BlogCategory::where('slug', 'destination-guides')->first()?->id,
                'title' => 'Complete Guide to Trekking in the Everest Region',
                'slug' => 'everest-region-trekking-guide',
                'excerpt' => 'Everything you need to know about trekking in the Khumbu — permits, routes, acclimatization, teahouses, and insider tips from our guides.',
                'content' => '<h2>Overview</h2>
<p>The Everest Region (Khumbu) is the world\'s most famous trekking destination. Home to Mount Everest (8,848.86m), it offers routes for every level — from the classic Everest Base Camp trek to the challenging Three Passes and technical peak climbing.</p>

<h2>Popular Routes</h2>
<h3>Everest Base Camp (12-14 Days)</h3>
<p>The classic route: Lukla → Phakding → Namche → Tengboche → Dingboche → Lobuche → Gorak Shep → EBC → Kala Patthar. Best for first-time Himalayan trekkers.</p>

<h3>Gokyo Lakes & Gokyo Ri (12-14 Days)</h3>
<p>Diverges from EBC route at Namche. Visits turquoise Gokyo Lakes, crosses Cho La Pass (optional), summits Gokyo Ri (5,357m) for arguably the best Everest panorama.</p>

<h3>Three Passes Trek (18-21 Days)</h3>
<p>The ultimate Khumbu traverse: Kongma La (5,535m), Cho La (5,420m), Renjo La (5,360m). Requires excellent fitness and prior high-altitude experience.</p>

<h3>Everest View Trek (7-9 Days)</h3>
<p>Shorter option reaching Tengboche (3,860m) and Everest View Hotel (3,880m). Ideal for those with limited time or altitude concerns.</p>

<h2>Permits Required</h2>
<ul>
<li><strong>Sagarmatha National Park Permit:</strong> NPR 3,000 (foreigners)</li>
<li><strong>Khumbu Pasang Lhamu Rural Municipality Permit:</strong> NPR 2,000</li>
<li><strong>TIMS Card:</strong> No longer required for Khumbu (replaced by local permit)</li>
</ul>

<h2>Getting There</h2>
<p><strong>Flight:</strong> 35 min Kathmandu → Lukla (weather dependent, delays common). Book early morning flights.</p>
<p><strong>Drive + Trek:</strong> 10-12 hrs Kathmandu → Phaplu (or Salleri), then 5-6 days trek to Namche. Good backup if flights cancelled.</p>

<h2>Acclimatization Strategy</h2>
<p>Our non-negotiable acclimatization days:</p>
<ul>
<li>Day 3-4: Namche Bazaar (3,440m) — hike to Everest View Hotel (3,880m)</li>
<li>Day 6-7: Dingboche (4,410m) — hike to Nangkartshang (5,100m)</li>
</ul>
<p>Additional day at Chhukung (4,730m) recommended for Three Passes and peak climbing.</p>

<h2>Teahouse Standards</h2>
<p>Lower elevations (up to Namche): Private rooms, attached bathrooms, hot showers, WiFi, varied menus.</p>
<p>Higher elevations (Dingboche+): Basic rooms (often shared), common squat toilets, bucket showers (extra), limited WiFi, simpler menus.</p>

<h2>Insider Tips from Our Guides</h2>
<ol>
<li><strong>Carry cash (NPR):</strong> No ATMs above Namche. Card machines rare.</li>
<li><strong>Water:</strong> Bring purification (tablets/filter). Bottled water expensive and creates plastic waste.</li>
<li><strong>Charging:</strong> Solar chargers unreliable. Bring 2-3 power banks (20,000mAh+). Pay for charging at teahouses.</li>
<li><strong>Lukla flights:</strong> Build 2 buffer days before international departure. Helicopter option exists (USD 500-600/person shared).</li>
<li><strong>Diamox:</strong> Consult your doctor. Our guides carry it for emergency use only.</li>
</ol>

<h2>When to Go</h2>
<p><strong>Peak Seasons:</strong> Oct-Nov (clear, busy), Mar-May (rhododendrons, warmer).</p>
<p><strong>Shoulder:</strong> Dec-Feb (cold, clear, quiet), Aug-Sep (monsoon tail, fewer crowds, flights more reliable).</p>

<h2>Why Choose Winterfell</h2>
<p>We don\'t do fixed departures. Every EBC trek is custom-designed around your dates, fitness, interests, and budget. Small groups (max 8), IFMGA-trained leaders, porter welfare guaranteed, satellite comms on every trip.</p>',
                'cover_image' => '/images/blog-everest-guide.jpg',
                'author' => 'Asmin Bhattarai',
                'published_at' => now()->subDays(10),
                'is_published' => true,
                'meta_title' => 'Everest Region Trekking Guide 2024 | Winterfell Treks',
                'meta_description' => 'Complete guide to Everest region trekking: routes, permits, acclimatization, teahouses, and expert tips from Winterfell guides.',
            ],
            [
                'category_id' => BlogCategory::where('slug', 'travel-tips')->first()?->id,
                'title' => 'High-Altitude Acclimatization: The Science & Practice',
                'slug' => 'high-altitude-acclimatization-guide',
                'excerpt' => 'Understanding how your body adapts to altitude, recognizing AMS/HAPE/HACE, and the acclimatization protocols that keep our clients safe.',
                'content' => '<h2>The Physics of Altitude</h2>
<p>At sea level, atmospheric pressure is 1013 mbar. At Everest Base Camp (5,364m), it\'s ~520 mbar — roughly half. Oxygen percentage stays 21%, but <strong>partial pressure</strong> drops proportionally. Your lungs rely on this pressure gradient to diffuse oxygen into blood.</p>

<h2>Acute Mountain Sickness (AMS)</h2>
<p>AMS is your body\'s response to hypoxia. Symptoms typically appear 6-24 hours after ascent:</p>
<ul>
<li>Headache (usually frontal, throbbing)</li>
<li>Nausea / loss of appetite</li>
<li>Dizziness / lightheadedness</li>
<li>Fatigue / weakness</li>
<li>Sleep disturbance</li>
</ul>
<p><strong>Diagnosis:</strong> Lake Louise Score ≥3 with headache + at least one other symptom.</p>

<h2>Life-Threatening Complications</h2>
<h3>HAPE (High-Altitude Pulmonary Edema)</h3>
<p>Fluid in lungs. Signs: breathlessness at rest, cough (pink frothy sputum), gurgling chest, extreme fatigue, cyanosis. <strong>Immediate descent mandatory.</strong></p>

<h3>HACE (High-Altitude Cerebral Edema)</h3>
<p>Brain swelling. Signs: ataxia (wobbly gait), confusion, hallucinations, severe headache unresponsive to meds, decreased consciousness. <strong>Immediate descent mandatory.</strong></p>

<h2>Acclimatization Physiology</h2>
<p>Your body adapts through:</p>
<ol>
<li><strong>Hyperventilation:</strong> Immediate. Blows off CO₂, raises blood pH (respiratory alkalosis).</li>
<li><strong>Renal compensation:</strong> 24-48 hrs. Kidneys excrete bicarbonate, normalize pH.</li>
<li><strong>Erythropoietin (EPO):</strong> Days-weeks. Increased red blood cell production.</li>
<li><strong>Capillary density:</strong> Weeks-months. Improved tissue oxygen delivery.</li>
</ol>

<h2>Our Acclimatization Protocol</h2>
<ul>
<li><strong>Ascend slowly:</strong> Max 300-500m sleeping elevation gain per day above 3,000m.</li>
<li><strong>Climb high, sleep low:</strong> Day hikes 300-500m above sleeping altitude.</li>
<li><strong>Rest days:</strong> Every 3-4 days, or every 1,000m gained.</li>
<li><strong>Hydration:</strong> 3-4L/day. Monitor urine color.</li>
<li><strong>Carbohydrate-rich diet:</strong> Easier to metabolize at altitude.</li>
<li><strong>No alcohol/sedatives:</strong> Suppress respiratory drive.</li>
</ul>

<h2>Medications</h2>
<p><strong>Acetazolamide (Diamox):</strong> Carbonic anhydrase inhibitor. Stimulates ventilation, speeds renal compensation. Prophylactic: 125mg 2x/day starting 24h before ascent. Therapeutic: 250mg 2x/day. Sulfa allergy contraindication.</p>
<p><strong>Dexamethasone:</strong> For HACE treatment only. 8mg stat, then 4mg 6-hourly. Not for prophylaxis.</p>
<p><strong>Nifedipine:</strong> For HAPE treatment/prevention in susceptible individuals.</p>

<h2>Red Flags — Immediate Descent</h2>
<ul>
<li>AMS symptoms worsening despite rest</li>
<li>Any HAPE/HACE signs</li>
<li>O₂ saturation <75% at rest (context-dependent)</li>
<li>Inability to walk heel-to-toe (ataxia test)</li>
</ul>

<h2>Winterfell Safety Standards</h2>
<p>Every expedition carries: Pulse oximeter, Lake Louise scoring sheets, oxygen cylinder (2L), Dexamethasone, Nifedipine, portable hyperbaric chamber (Gamow bag) on trips >5,500m. Guides trained in wilderness medicine (WFR/WEMT). Satellite communication for evacuation coordination.</p>',
                'cover_image' => '/images/blog-acclimatization.jpg',
                'author' => 'Dr. Pemba Sherpa (Medical Advisor)',
                'published_at' => now()->subDays(25),
                'is_published' => true,
                'meta_title' => 'High-Altitude Acclimatization Guide | Winterfell Treks',
                'meta_description' => 'Science-based guide to altitude acclimatization, AMS/HAPE/HACE recognition, and safety protocols from Winterfell medical advisors.',
            ],
            [
                'category_id' => BlogCategory::where('slug', 'travel-advice')->first()?->id,
                'title' => 'Packing List for Himalayan Trekking: The Essentials',
                'slug' => 'himalayan-trekking-packing-list',
                'excerpt' => 'Our field-tested packing list for Himalayan treks. What to bring, what to leave, and what we provide.',
                'content' => '<h2>Core Principle: Layering System</h2>
<p>Himalayan weather changes rapidly. A proper layering system lets you adapt instantly.</p>

<h3>Base Layer (Worn Next to Skin)</h3>
<ul>
<li>2x Merino wool or synthetic long-sleeve tops</li>
<li>1x Merino wool or synthetic long johns</li>
<li><strong>No cotton</strong> — retains moisture, causes hypothermia</li>
</ul>

<h3>Mid Layer (Insulation)</h3>
<ul>
<li>1x Fleece jacket (Polartec 200+ weight)</li>
<li>1x Lightweight down jacket or synthetic insulated jacket</li>
</ul>

<h3>Outer Layer (Protection)</h3>
<ul>
<li>1x Hardshell jacket (Gore-Tex or equivalent, 3-layer)</li>
<li>1x Hardshell pants (full side zips for boots)</li>
</ul>

<h3>Extreme Cold Layer (Above 4,500m / Summit Days)</h3>
<ul>
<li>1x Expedition down jacket (800+ fill, 200g+ fill weight)</li>
<li>1x Down pants or heavy fleece pants</li>
<li>1x Expedition mittens (down, windproof shell)</li>
<li>1x Balaclava or neck gaiter + warm beanie</li>
</ul>

<h2>Footwear</h2>
<ul>
<li><strong>Trekking boots:</strong> Broken-in, waterproof, semi-rigid sole (B1/B2). Size up 0.5-1 for thick socks + swelling.</li>
<li><strong>Camp shoes:</strong> Lightweight Crocs, down booties, or sandals for evenings.</li>
<li><strong>Socks:</strong> 4-5x Merino wool trekking socks, 1-2x liner socks, 1x thick sleeping socks.</li>
<li><strong>Gaiters:</strong> Essential for snow, scree, mud.</li>
</ul>

<h2>Backpacks & Storage</h2>
<ul>
<li><strong>Daypack:</strong> 30-35L (carry water, camera, layers, snacks)</li>
<li><strong>Duffel bag:</strong> 80-100L (for porter, max 15-20kg)</li>
<li><strong>Dry bags / stuff sacks:</strong> Organize and waterproof gear inside duffel</li>
<li><strong>Rain cover:</strong> For daypack</li>
</ul>

<h2>Sleep System</h2>
<ul>
<li><strong>Sleeping bag:</strong> Rated -10°C to -20°C comfort (down preferred). We provide on request.</li>
<li><strong>Sleeping bag liner:</strong> Silk or fleece (adds warmth, keeps bag clean)</li>
<li><strong>Inflatable sleeping pad:</strong> R-value 4+ (we provide foam pad)</li>
</ul>

<h2>Accessories</h2>
<ul>
<li><strong>Headlamp:</strong> 300+ lumens, red light mode, spare batteries (cold kills batteries)</li>
<li><strong>Sunglasses:</strong> Category 3-4, wrap-around, side shields</li>
<li><strong>Ski goggles:</strong> For summit days / high wind</li>
<li><strong>Trekking poles:</strong> Collapsible, carbon fiber (reduce knee impact 25%)</li>
<li><strong>Water bottles:</strong> 2x 1L wide-mouth Nalgene (insulated sleeves)</li>
<li><strong>Water purification:</strong> Tablets (chlorine dioxide) or filter (Sawyer/Katadyn)</li>
<li><strong>Sunscreen:</strong> SPF 50+, broad spectrum, lip balm SPF 30+</li>
<li><strong>First aid (personal):</strong> Blister kit (Leukotape, Compeed), ibuprofen, imodium, antihistamine, personal meds</li>
</ul>

<h2>Electronics</h2>
<ul>
<li>Phone + power bank (20,000mAh+)</li>
<li>Camera + spare batteries (keep warm!)</li>
<li>Universal travel adapter (Type C/D/M)</li>
<li>E-reader / Kindle (saves weight on books)</li>
</ul>

<h2>What We Provide</h2>
<ul>
<li>Sleeping bag (-20°C rated) — on request</li>
<li>Foam sleeping pad</li>
<li>Down jacket — on request (limited sizes)</li>
<li>Duffel bag for porter</li>
<li>Group medical kit, oxygen, satellite phone</li>
<li>All permits, park fees</li>
<li>Teahouse accommodation & meals</li>
</ul>

<h2>Leave at Home</h2>
<ul>
<li>Cotton clothing (jeans, hoodies, t-shirts)</li>
<li>Excessive toiletries (teahouses have basics)</li>
<li>Heavy books</li>
<li>Alcohol (buy in Kathmandu if desired)</li>
<li>Drone (without prior CAAN permit — 30+ day process)</li>
</ul>

<h2>Pro Tips</h2>
<ol>
<li>Pack duffel so porter can access layers without unpacking everything.</li>
<li>Wear heaviest items on plane (boots, down jacket).</li>
<li>Keep passport, permits, cash, meds in daypack — never in duffel.</li>
<li>Test all gear before departure. Break in boots 50+ km.</li>
</ol>',
                'cover_image' => '/images/blog-packing.jpg',
                'author' => 'Winterfell Gear Team',
                'published_at' => now()->subDays(40),
                'is_published' => true,
                'meta_title' => 'Himalayan Trekking Packing List | Winterfell Treks',
                'meta_description' => 'Complete field-tested packing list for Himalayan treks. Layering system, footwear, sleep gear, and what Winterfell provides.',
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::updateOrCreate(['slug' => $post['slug']], $post);
        }
    }
}