<template>
    <q-toolbar class="container flex justify-between items-center">
        <div class="flex q-gutter-sm">
            <img src="/assets/images/footer-logo.png"/>
            <div>
                <div>Crafted with care by</div>
                <div>Mizoram State e-Governance Society</div>
            </div>
        </div>
        <div class="column items-center">
            <div class="nav-link">Help Desk</div>
            <div class="nav-link">support-msegs@mizoram.gov.in/9233942951</div>
        </div>
        <div class="column items-end">
            <div>© 2024 kai-GoM. All rights reserved</div>
            <div class="flex q-gutter-sm">
                <a class="footer-link" :href="route('page.privacy')">Privacy Policy</a>
                <a class="footer-link" :href="route('page.term')">Terms and condition</a>
            </div>
        </div>
    </q-toolbar>
</template>
<style scoped>
.footer-link{
    text-decoration: none;
    color: white;
    font-size: 12px;
}
</style>
