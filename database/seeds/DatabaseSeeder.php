<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(AdminLanguageSeeder::class);
        $this->call(AdminUserConversationSeeder::class);
        $this->call(AdminUserMessageSeeder::class);
        $this->call(AttributeSeeder::class);
        $this->call(AttributeOptionSeeder::class);
        $this->call(BannerSeeder::class);
        $this->call(BlogSeeder::class);
        $this->call(BlogCategorySeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ChildCategorySeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(CouponSeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(EmailTemplateSeeder::class);
        $this->call(FaqSeeder::class);
        $this->call(GeneralSettingSeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(NotificationSeeder::class);
        $this->call(OrderSeeder::class);
        $this->call(OrderTrackSeeder::class);
        $this->call(PackageSeeder::class);
        $this->call(PageSeeder::class);
        $this->call(PageSettingSeeder::class);
        $this->call(PaymentGatewaySeeder::class);
        $this->call(PickupSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(SeoToolSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(SliderSeeder::class);
        $this->call(SocialSettingSeeder::class);
        $this->call(SocialProviderSeeder::class);
        $this->call(SubcategorySeeder::class);
        $this->call(SubscriberSeeder::class);
        $this->call(UserNotificationSeeder::class);
        $this->call(UserSubscriptionSeeder::class);
        $this->call(WishlistSeeder::class);
        $this->call(WithdrawSeeder::class);
    }
}
