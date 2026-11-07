<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FAQ;

class FAQSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            // General Questions
            ['category' => 'General', 'question' => 'What services does Winterfell Treks & Expedition provide?', 'answer' => '<p>We specialize in customized Himalayan trekking, mountaineering expeditions, peak climbing, cultural immersion experiences, photography expeditions, wildlife tracking, and monastery retreats in Nepal. Every itinerary is designed from scratch based on your goals, fitness, timeline, and interests. We do not offer fixed-departure group tours or pre-packaged "off-the-shelf" trips.</p>', 'sort_order' => 1, 'is_published' => true],
            ['category' => 'General', 'question' => 'Which destinations do you cover?', 'answer' => '<p>We operate across all major trekking regions of Nepal: Everest (Khumbu), Annapurna, Langtang, Manaslu, Upper Mustang, Nar Phu, Kanchenjunga, Makalu, Dolpo, and off-the-beaten-path valleys. We also arrange trips to Tibet (Kailash, Everest North Side) and Bhutan through trusted partners.</p>', 'sort_order' => 2, 'is_published' => true],
            ['category' => 'General', 'question' => 'Do you offer customized travel packages?', 'answer' => '<p>Yes — customization is our <strong>only</strong> model. We don\'t have fixed itineraries. After an initial consultation, we design a detailed day-by-day plan including acclimatization strategy, cultural interests, photography goals, fitness considerations, and budget. You review and refine until it\'s perfect.</p>', 'sort_order' => 3, 'is_published' => true],

            // Package Questions
            ['category' => 'Packages', 'question' => 'Can I modify a tour package?', 'answer' => '<p>Absolutely. Since every itinerary is custom-designed, modifications are expected and welcome — before booking and even during the trek (within safety limits). Want an extra rest day? A side trip to a monastery? A different route? We adapt.</p>', 'sort_order' => 1, 'is_published' => true],
            ['category' => 'Packages', 'question' => 'What is included in a package?', 'answer' => '<p>Standard inclusions: all airport transfers, Kathmandu hotel (B/B), domestic flights, all permits (national park, restricted area, TIMS), experienced English-speaking guide (1:4 ratio), porters (1:2, max 20kg), all teahouse accommodation, all meals on trek (B/L/D), guide/porter insurance/wages/equipment, group medical kit, oximeter, satellite phone, welcome/farewell dinners, government taxes. Climbing packages add: peak permit, climbing guide (1:2), base camp tented camp, high camp gear, fixed ropes, pre-climb training.</p>', 'sort_order' => 2, 'is_published' => true],
            ['category' => 'Packages', 'question' => 'Are the prices fixed?', 'answer' => '<p>We provide a detailed quotation valid for 30 days. Prices may change if: government permit fees increase, domestic flight costs change, exchange rate shifts significantly (>5%), or you modify the itinerary. We communicate any changes transparently before you commit.</p>', 'sort_order' => 3, 'is_published' => true],

            // Enquiry Questions
            ['category' => 'Enquiries', 'question' => 'How do I enquire about a package?', 'answer' => '<p>Click "Enquire Now" on any package/destination page, fill the form, or email enquiries@winterfelltreks.com. We respond within 24 hours with initial ideas and questions to start the customization process. No obligation.</p>', 'sort_order' => 1, 'is_published' => true],
            ['category' => 'Enquiries', 'question' => 'How long does it take to receive a response?', 'answer' => '<p>Initial response within 24 hours (often faster). A draft itinerary and quotation typically within 48-72 hours after we understand your preferences. Complex expeditions (multiple peaks, remote regions) may take 5-7 days.</p>', 'sort_order' => 2, 'is_published' => true],
            ['category' => 'Enquiries', 'question' => 'Can I enquire about a custom itinerary?', 'answer' => '<p>Yes — that\'s exactly what we do. Use the General Enquiry form and describe your dream trip: regions, dates, group size, fitness, interests, budget range. We\'ll craft something from scratch.</p>', 'sort_order' => 3, 'is_published' => true],

            // Travel Questions
            ['category' => 'Travel', 'question' => 'What documents should I carry?', 'answer' => '<p><strong>Essential:</strong> Passport (6+ months validity), Nepal visa (on arrival for most nationalities), travel insurance certificate (must cover high-altitude trekking to your max elevation), emergency contacts. <strong>Recommended:</strong> Photocopies of passport/visa/insurance, passport photos (for permits), vaccination records.</p>', 'sort_order' => 1, 'is_published' => true],
            ['category' => 'Travel', 'question' => 'Do I need travel insurance?', 'answer' => '<p><strong>Mandatory.</strong> Your policy must explicitly cover: trekking to your maximum altitude (e.g., "up to 6,200m" for Island Peak), emergency helicopter evacuation, medical treatment/repatriation, trip cancellation/interruption. Standard travel insurance often excludes >4,000m. We recommend Global Rescue, World Nomads, or specialized mountaineering policies.</p>', 'sort_order' => 2, 'is_published' => true],
            ['category' => 'Travel', 'question' => 'What should I prepare before travelling?', 'answer' => '<p><strong>Fitness:</strong> Cardiovascular base (hike 10-15km with 8-10kg pack weekly), leg strength, core. <strong>Medical:</strong> Dental check-up, discuss Diamox with doctor, update vaccinations (Hep A/B, Typhoid, Tetanus, Japanese Encephalitis). <strong>Gear:</strong> Break in boots 50+ km, test layering system, charge all electronics. <strong>Admin:</strong> Visa photos, insurance documents, emergency contacts shared with family.</p>', 'sort_order' => 3, 'is_published' => true],
            ['category' => 'Travel', 'question' => 'What is the best time to trek in Nepal?', 'answer' => '<p><strong>Oct-Nov (Post-monsoon):</strong> Clearest skies, stable weather, best views, busiest trails. <strong>Mar-May (Pre-monsoon):</strong> Rhododendron blooms, warmer, occasional afternoon clouds, second busiest. <strong>Dec-Feb (Winter):</strong> Cold, very clear, quiet, some high passes snowed in. <strong>Jun-Sep (Monsoon):</strong> Rain, leeches, obscured views — except rain shadow areas (Upper Mustang, Nar Phu, Dolpo) which are excellent.</p>', 'sort_order' => 4, 'is_published' => true],
            ['category' => 'Travel', 'question' => 'How do I get a Nepal visa?', 'answer' => '<p>Most nationalities get visa on arrival at Kathmandu airport: 15 days (USD 30), 30 days (USD 50), 90 days (USD 125). Bring USD cash (clean, uncreased notes) and 1 passport photo. Online pre-application available at <a href="https://nepalimmigration.gov.np" target="_blank">nepalimmigration.gov.np</a>. Indian nationals: no visa required (government ID sufficient).</p>', 'sort_order' => 5, 'is_published' => true],
            ['category' => 'Travel', 'question' => 'What about altitude sickness?', 'answer' => '<p>We take altitude seriously. Every itinerary includes mandatory acclimatization days. Guides carry oximeters, conduct daily Lake Louise scoring, and have authority to halt ascent/evacuate. We carry oxygen, Dexamethasone, Nifedipine, and Gamow bag (on trips >5,500m). <strong>Golden rule:</strong> Any altitude illness = descend immediately. No summit is worth your life.</p>', 'sort_order' => 6, 'is_published' => true],
        ];

        foreach ($faqs as $faq) {
            FAQ::updateOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }
    }
}