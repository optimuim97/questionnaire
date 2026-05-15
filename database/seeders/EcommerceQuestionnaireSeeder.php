<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Questionnaire;
use Illuminate\Database\Seeder;

class EcommerceQuestionnaireSeeder extends Seeder
{
    public function run(): void
    {
        // Idempotent : ne crée le questionnaire qu'une seule fois
        if (Questionnaire::where('title', 'Conception de votre site e-commerce')->exists()) {
            $this->command->info('✓ Questionnaire e-commerce déjà présent, skip.');
            return;
        }

        $questionnaire = Questionnaire::create([
            'title'       => 'Conception de votre site e-commerce',
            'description' => 'Ce questionnaire nous permet de mieux comprendre vos besoins afin de vous proposer une solution sur mesure. Comptez environ 10 minutes.',
            'is_active'   => true,
        ]);

        $questions = [

            // ── 1. INFORMATIONS GÉNÉRALES ──────────────────────────────────
            [
                'title'      => 'Quel est le nom de votre entreprise / marque ?',
                'explanation' => 'Tel qu\'il apparaîtra sur votre site.',
                'input_type' => 'text',
                'required'   => true,
            ],
            [
                'title'      => 'Décrivez brièvement votre activité et les produits que vous vendez.',
                'explanation' => 'Ex : vente de vêtements pour femmes, cosmétiques naturels, équipements sportifs…',
                'input_type' => 'textarea',
                'required'   => true,
            ],
            [
                'title'      => 'Dans quel secteur opérez-vous ?',
                'input_type' => 'select',
                'required'   => true,
                'options'    => [
                    'Mode & Accessoires',
                    'Beauté & Cosmétiques',
                    'Alimentation & Boissons',
                    'High-Tech & Électronique',
                    'Maison & Décoration',
                    'Sport & Loisirs',
                    'Santé & Bien-être',
                    'Enfants & Jouets',
                    'Librairie & Numérique',
                    'Artisanat & Fait main',
                    'Autre',
                ],
            ],
            [
                'title'      => 'Avez-vous déjà un site web existant ?',
                'input_type' => 'radio',
                'required'   => true,
                'options'    => ['Oui, je veux le refaire', 'Oui, je veux l\'améliorer', 'Non, c\'est un nouveau projet'],
            ],
            [
                'title'      => 'Si oui, quelle est l\'URL de votre site actuel ?',
                'explanation' => 'Laissez vide si vous n\'avez pas de site.',
                'input_type' => 'text',
                'required'   => false,
            ],

            // ── 2. IDENTITÉ VISUELLE ────────────────────────────────────────
            [
                'title'      => 'Avez-vous déjà un logo ?',
                'input_type' => 'radio',
                'required'   => true,
                'options'    => ['Oui, j\'ai un logo finalisé', 'En cours de création', 'Non, j\'en ai besoin'],
            ],
            [
                'title'      => 'Quelles couleurs représentent le mieux votre marque ?',
                'explanation' => 'Sélectionnez jusqu\'à 3 couleurs dominantes souhaitées.',
                'input_type' => 'checkbox',
                'required'   => false,
                'options'    => ['Noir', 'Blanc', 'Gris', 'Bleu', 'Bleu marine', 'Vert', 'Rouge', 'Rose', 'Orange', 'Jaune', 'Doré / Champagne', 'Marron / Beige', 'Violet'],
            ],
            [
                'title'      => 'Quel style visuel correspond le mieux à votre marque ?',
                'input_type' => 'radio',
                'required'   => true,
                'options'    => [
                    'Moderne & Minimaliste',
                    'Luxe & Premium',
                    'Coloré & Dynamique',
                    'Naturel & Écologique',
                    'Jeune & Tendance (streetwear, pop…)',
                    'Classique & Élégant',
                ],
            ],
            [
                'title'      => 'Avez-vous des sites de référence dont vous aimez le design ?',
                'explanation' => 'Indiquez les URLs ou les noms des marques qui vous inspirent.',
                'input_type' => 'textarea',
                'required'   => false,
            ],

            // ── 3. CATALOGUE PRODUITS ───────────────────────────────────────
            [
                'title'      => 'Combien de produits comptez-vous vendre au lancement ?',
                'input_type' => 'select',
                'required'   => true,
                'options'    => ['Moins de 20', '20 – 100', '100 – 500', '500 – 2 000', 'Plus de 2 000'],
            ],
            [
                'title'      => 'Quel(s) type(s) de produits allez-vous proposer ?',
                'input_type' => 'checkbox',
                'required'   => true,
                'options'    => [
                    'Produits physiques (avec livraison)',
                    'Produits numériques / téléchargeables',
                    'Services (réservation, prestation)',
                    'Abonnements récurrents',
                ],
            ],
            [
                'title'      => 'Vos produits auront-ils des variantes ?',
                'explanation' => 'Ex : tailles (S/M/L), couleurs, formats, etc.',
                'input_type' => 'radio',
                'required'   => true,
                'options'    => ['Oui', 'Non', 'Certains seulement'],
            ],
            [
                'title'      => 'Souhaitez-vous une gestion des stocks automatisée ?',
                'explanation' => 'Alertes stock bas, blocage commande si rupture…',
                'input_type' => 'radio',
                'required'   => true,
                'options'    => ['Oui, indispensable', 'Oui, si possible', 'Non, je gère manuellement'],
            ],

            // ── 4. MOYENS DE PAIEMENT ───────────────────────────────────────
            [
                'title'      => 'Quels moyens de paiement souhaitez-vous intégrer ?',
                'input_type' => 'checkbox',
                'required'   => true,
                'options'    => [
                    'Carte bancaire (Visa / Mastercard)',
                    'PayPal',
                    'Stripe',
                    'Apple Pay / Google Pay',
                    'Virement bancaire',
                    'Paiement à la livraison',
                    'Mobile Money (Orange Money, Wave…)',
                    'Chèque',
                    'Cryptomonnaie',
                ],
            ],
            [
                'title'      => 'Souhaitez-vous proposer le paiement en plusieurs fois ?',
                'input_type' => 'radio',
                'required'   => true,
                'options'    => ['Oui, dès le lancement', 'Oui, dans un second temps', 'Non'],
            ],
            [
                'title'      => 'Dans quelle(s) devise(s) souhaitez-vous vendre ?',
                'input_type' => 'checkbox',
                'required'   => true,
                'options'    => ['Euro (€)', 'Dollar US ($)', 'Livre sterling (£)', 'Franc CFA (XOF)', 'Dirham marocain (MAD)', 'Dinar algérien (DZD)', 'Autre'],
            ],

            // ── 5. LIVRAISON ────────────────────────────────────────────────
            [
                'title'      => 'Vers quelles zones souhaitez-vous livrer ?',
                'input_type' => 'checkbox',
                'required'   => true,
                'options'    => [
                    'Locale / ville uniquement',
                    'Nationale',
                    'Europe',
                    'Afrique',
                    'International (monde entier)',
                    'Click & Collect (retrait en boutique)',
                ],
            ],
            [
                'title'      => 'Avez-vous déjà des partenaires transporteurs en tête ?',
                'input_type' => 'checkbox',
                'required'   => false,
                'options'    => [
                    'Colissimo (La Poste)',
                    'Chronopost',
                    'DHL',
                    'DPD',
                    'UPS',
                    'FedEx',
                    'Mondial Relay',
                    'Transporteur local',
                    'Je gère moi-même',
                    'Pas encore décidé',
                ],
            ],
            [
                'title'      => 'Comment souhaitez-vous calculer les frais de livraison ?',
                'input_type' => 'checkbox',
                'required'   => true,
                'options'    => [
                    'Forfait fixe par commande',
                    'Selon le poids / volume',
                    'Selon la zone géographique',
                    'Livraison gratuite à partir d\'un montant',
                    'Offerte sur toutes les commandes',
                ],
            ],
            [
                'title'      => 'Souhaitez-vous intégrer un suivi de commande en temps réel ?',
                'input_type' => 'radio',
                'required'   => true,
                'options'    => ['Oui, indispensable', 'Oui, si possible', 'Non'],
            ],

            // ── 6. DASHBOARD & GESTION ──────────────────────────────────────
            [
                'title'      => 'Quelles fonctionnalités souhaitez-vous dans votre tableau de bord ?',
                'explanation' => 'Sélectionnez tout ce dont vous avez besoin.',
                'input_type' => 'checkbox',
                'required'   => true,
                'options'    => [
                    'Statistiques des ventes (CA, commandes, panier moyen)',
                    'Gestion des commandes (statuts, historique)',
                    'Gestion des clients (fiches, historique achats)',
                    'Gestion des produits / catalogue',
                    'Gestion des stocks',
                    'Codes promo & réductions',
                    'Avis et évaluations clients',
                    'Export des données (Excel / CSV)',
                    'Gestion des retours / remboursements',
                    'Rapports et graphiques avancés',
                ],
            ],
            [
                'title'      => 'Aurez-vous plusieurs personnes à gérer le site ?',
                'explanation' => 'Ex : un admin, un gestionnaire stock, un SAV…',
                'input_type' => 'radio',
                'required'   => true,
                'options'    => ['Non, je suis seul', 'Oui, 2 – 3 personnes', 'Oui, plus de 3 personnes'],
            ],
            [
                'title'      => 'Souhaitez-vous des notifications automatiques par email ?',
                'input_type' => 'checkbox',
                'required'   => false,
                'options'    => [
                    'Confirmation de commande (client)',
                    'Expédition / numéro de suivi (client)',
                    'Nouvelle commande reçue (admin)',
                    'Stock bas (admin)',
                    'Paiement reçu (admin)',
                    'Abandon de panier (client)',
                ],
            ],

            // ── 7. MARKETING & FIDÉLISATION ─────────────────────────────────
            [
                'title'      => 'Quels outils marketing souhaitez-vous intégrer ?',
                'input_type' => 'checkbox',
                'required'   => false,
                'options'    => [
                    'Blog / actualités',
                    'Programme de fidélité (points, récompenses)',
                    'Codes promo & ventes flash',
                    'Avis clients / système de notation',
                    'Wishlist (liste de souhaits)',
                    'Recommandations de produits ("Vous aimerez aussi")',
                    'Newsletter / email marketing',
                    'Partage sur les réseaux sociaux',
                    'Pixel Facebook / Google Analytics',
                ],
            ],
            [
                'title'      => 'Avez-vous des réseaux sociaux actifs liés à votre activité ?',
                'input_type' => 'checkbox',
                'required'   => false,
                'options'    => ['Instagram', 'Facebook', 'TikTok', 'YouTube', 'Pinterest', 'LinkedIn', 'WhatsApp Business', 'Aucun pour l\'instant'],
            ],

            // ── 8. TECHNIQUE & PROJET ───────────────────────────────────────
            [
                'title'      => 'En combien de langues souhaitez-vous votre site ?',
                'input_type' => 'checkbox',
                'required'   => true,
                'options'    => ['Français', 'Anglais', 'Arabe', 'Espagnol', 'Portugais', 'Autre'],
            ],
            [
                'title'      => 'Avez-vous un nom de domaine et un hébergement ?',
                'input_type' => 'radio',
                'required'   => true,
                'options'    => [
                    'Oui, les deux',
                    'J\'ai seulement le nom de domaine',
                    'Non, j\'ai besoin des deux',
                    'Je ne sais pas',
                ],
            ],
            [
                'title'      => 'Quel est votre budget estimé pour ce projet ?',
                'input_type' => 'select',
                'required'   => false,
                'options'    => [
                    'Moins de 500 €',
                    '500 € – 1 500 €',
                    '1 500 € – 3 000 €',
                    '3 000 € – 7 000 €',
                    '7 000 € – 15 000 €',
                    'Plus de 15 000 €',
                    'Je préfère ne pas l\'indiquer',
                ],
            ],
            [
                'title'      => 'Dans quel délai souhaitez-vous lancer votre site ?',
                'input_type' => 'select',
                'required'   => true,
                'options'    => [
                    'Le plus vite possible (< 1 mois)',
                    '1 à 2 mois',
                    '2 à 4 mois',
                    '4 à 6 mois',
                    'Plus de 6 mois',
                    'Pas encore décidé',
                ],
            ],
            [
                'title'      => 'Souhaitez-vous une maintenance et un support après la livraison ?',
                'input_type' => 'radio',
                'required'   => true,
                'options'    => ['Oui, avec un contrat de maintenance', 'Oui, ponctuellement', 'Non, je me débrouillerai seul'],
            ],

            // ── 9. INFORMATIONS DE CONTACT ──────────────────────────────────
            [
                'title'      => 'Quel est votre numéro de téléphone / WhatsApp ?',
                'explanation' => 'Pour vous recontacter facilement.',
                'input_type' => 'phone',
                'required'   => true,
            ],
            [
                'title'      => 'Y a-t-il des informations complémentaires à nous communiquer ?',
                'explanation' => 'Fonctionnalités spécifiques, contraintes techniques, partenaires obligatoires…',
                'input_type' => 'textarea',
                'required'   => false,
            ],
        ];

        foreach ($questions as $order => $data) {
            Question::create([
                'questionnaire_id' => $questionnaire->id,
                'order'            => $order,
                ...$data,
            ]);
        }

        $this->command->info("✓ Questionnaire e-commerce créé avec " . count($questions) . " questions.");
    }
}
