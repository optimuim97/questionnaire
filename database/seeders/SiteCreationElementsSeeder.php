<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Questionnaire;
use Illuminate\Database\Seeder;

class SiteCreationElementsSeeder extends Seeder
{
    public function run(): void
    {
        if (Questionnaire::where('title', 'Demande des éléments pour la création du site')->exists()) {
            $this->command->info('✓ Questionnaire éléments site déjà présent, skip.');
            return;
        }

        $questionnaire = Questionnaire::create([
            'title'       => 'Demande des éléments pour la création du site',
            'description' => 'Afin de démarrer la création de votre site, nous avons besoin de quelques éléments clés. Merci de renseigner ce formulaire — cela nous prendra environ 5 minutes.',
            'is_active'   => true,
        ]);

        $questions = [

            // ── 1. PAIEMENTS ────────────────────────────────────────────────
            [
                'title'       => 'Quel est votre numéro Wave Business ?',
                'explanation' => 'Ce numéro sera utilisé pour recevoir les paiements Wave sur votre site.',
                'input_type'  => 'phone',
                'required'    => false,
            ],
            [
                'title'       => 'Quel est votre numéro Orange Money Business ?',
                'explanation' => 'Ce numéro sera utilisé pour recevoir les paiements Orange Money sur votre site.',
                'input_type'  => 'phone',
                'required'    => false,
            ],

            // ── 2. RÉSEAUX SOCIAUX & MARKETING ─────────────────────────────
            [
                'title'       => 'Pouvez-vous nous donner accès à votre page Facebook Business ?',
                'explanation' => 'Nécessaire pour installer le Pixel Meta et activer le suivi des conversions.',
                'input_type'  => 'radio',
                'required'    => true,
                'options'     => [
                    'Oui, je peux donner l\'accès',
                    'Je n\'ai pas de page Facebook Business',
                    'Je vais créer ma page',
                ],
            ],
            [
                'title'       => 'Pouvez-vous nous donner accès à votre compte TikTok Business ?',
                'explanation' => 'Nécessaire pour installer le Pixel TikTok et activer le suivi des conversions.',
                'input_type'  => 'radio',
                'required'    => true,
                'options'     => [
                    'Oui, je peux donner l\'accès',
                    'Je n\'ai pas de compte TikTok Business',
                    'Je vais créer mon compte',
                ],
            ],
            [
                'title'       => 'Le numéro 07 07 84 98 83 est-il bien votre numéro WhatsApp Business confirmé ?',
                'explanation' => 'Veuillez vérifier que ce numéro est bien associé à un compte WhatsApp Business (et non un compte personnel).',
                'input_type'  => 'radio',
                'required'    => true,
                'options'     => [
                    'Oui, c\'est mon compte WhatsApp Business',
                    'Non, c\'est un compte personnel',
                    'Je ne suis pas sûr(e)',
                ],
            ],
            [
                'title'       => 'Avez-vous déjà un compte Google Analytics 4 ?',
                'explanation' => 'Google Analytics 4 permet de suivre le trafic et le comportement des visiteurs sur votre site.',
                'input_type'  => 'radio',
                'required'    => true,
                'options'     => [
                    'Oui, j\'en ai déjà un (je vous donne l\'accès)',
                    'Non, nous allons le créer ensemble',
                ],
            ],
            [
                'title'       => 'Avez-vous déjà un compte Brevo / Sendinblue pour la newsletter ?',
                'explanation' => 'Brevo (anciennement Sendinblue) est utilisé pour gérer vos campagnes d\'emailing et newsletters.',
                'input_type'  => 'radio',
                'required'    => true,
                'options'     => [
                    'Oui, j\'en ai déjà un (je vous donne l\'accès)',
                    'Non, nous allons le créer ensemble',
                ],
            ],

            // ── 3. IDENTITÉ VISUELLE ────────────────────────────────────────
            [
                'title'       => 'Votre logo est-il disponible dans les formats suivants ?',
                'explanation' => 'Cochez les formats que vous possédez déjà.',
                'input_type'  => 'file',
                'required'    => true
            ],
            [
                'title'       => 'Votre charte couleurs inclut-elle uniquement le rose (#E8336D) et le blanc ?',
                'explanation' => 'Si vous avez d\'autres couleurs officielles, merci de les préciser dans la question suivante.',
                'input_type'  => 'radio',
                'required'    => true,
                'options'     => [
                    'Oui, uniquement ces deux couleurs',
                    'Non, j\'ai des couleurs supplémentaires à préciser',
                ],
            ],
            [
                'title'       => 'Si vous avez des couleurs supplémentaires, précisez les codes couleur.',
                'explanation' => 'Indiquez les codes HEX, RGB ou Pantone. Ex : Bleu marine #1A2B4C, Doré #C9A84C…',
                'input_type'  => 'textarea',
                'required'    => false,
            ],
            [
                'title'       => 'Utilisez-vous Playfair Display et Inter comme polices officielles de votre marque ?',
                'explanation' => 'Ces polices seront utilisées sur l\'ensemble de votre site.',
                'input_type'  => 'radio',
                'required'    => true,
                'options'     => [
                    'Oui, Playfair Display et Inter me conviennent',
                    'Non, j\'utilise d\'autres polices',
                    'Je n\'ai pas encore défini mes polices',
                ],
            ],
            [
                'title'       => 'Si vous utilisez d\'autres polices, lesquelles ?',
                'explanation' => 'Précisez le nom exact des polices (ex : Montserrat, Lora, Raleway…).',
                'input_type'  => 'text',
                'required'    => false,
            ],
        ];

        foreach ($questions as $order => $data) {
            Question::create([
                'questionnaire_id' => $questionnaire->id,
                'order'            => $order,
                ...$data,
            ]);
        }

        $this->command->info("✓ Questionnaire éléments site créé avec " . count($questions) . " questions.");
    }
}
