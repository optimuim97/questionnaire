import AdminLayout from '../components/admin/AdminLayout.vue';
import AdminLogin from '../components/admin/AdminLogin.vue';
import AdminHome from '../components/admin/AdminHome.vue';
import QuestionnaireForm from '../components/admin/QuestionnaireForm.vue';
import QuestionnaireEdit from '../components/admin/QuestionnaireEdit.vue';
import QuestionnaireResponses from '../components/admin/QuestionnaireResponses.vue';
import ClientQuestionnaire from '../components/client/ClientQuestionnaire.vue';
import { useAuth } from '../composables/useAuth.js';

const routes = [
    {
        path: '/admin/login',
        name: 'admin.login',
        component: AdminLogin,
        meta: { guest: true },
    },
    {
        path: '/admin',
        component: AdminLayout,
        meta: { requiresAuth: true },
        children: [
            { path: '', name: 'admin.home', component: AdminHome },
            { path: 'questionnaires/create', name: 'admin.questionnaires.create', component: QuestionnaireForm },
            { path: 'questionnaires/:id/edit', name: 'admin.questionnaires.edit', component: QuestionnaireEdit },
            { path: 'questionnaires/:id/responses', name: 'admin.questionnaires.responses', component: QuestionnaireResponses },
        ],
    },
    {
        path: '/q/:id',
        name: 'client.questionnaire',
        component: ClientQuestionnaire,
    },
    {
        path: '/',
        redirect: '/admin',
    },
];

export default routes;

export async function setupGuard(router) {
    const { user, checked, fetchUser } = useAuth();

    router.beforeEach(async (to) => {
        // Fetch user once on first navigation
        if (!checked.value) {
            await fetchUser();
        }

        if (to.meta.requiresAuth && !user.value) {
            return { name: 'admin.login', query: { redirect: to.fullPath } };
        }

        if (to.meta.guest && user.value) {
            return { name: 'admin.home' };
        }
    });
}
