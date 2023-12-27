<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

//require __DIR__.'/auth.php';

/* Frontend Routes */

Route::get('/', 'HomeController@index')->name('home');
Route::get('/insights', 'InsightController@index')->name('insights');
Route::post('/insights/ajax', 'InsightController@ajax')->name('insights.ajax');
Route::get('/insights/{slug}', 'InsightController@details')->name('insights.details');
Route::get('/services', 'ServiceController@index')->name('services');
Route::get('/services/{slug}', 'ServiceController@details')->name('services.details');
Route::get('/contact-us', 'HomeController@contactPage')->name('contact-us');
Route::post('/bookAppointment', 'HomeController@bookAppointment')->name('book-appointment');
Route::get('/about-us', 'HomeController@aboutPage')->name('about-us');
Route::post('/saveContactUs', 'HomeController@saveContactUs');
Route::get('/case-studies', 'CaseStudyController@index')->name('case-studies');
Route::post('/case-studies/ajax', 'CaseStudyController@ajax')->name('case-studies.ajax');
Route::get('/case-studies/{slug}', 'CaseStudyController@details')->name('case-studies.details');
Route::get('/careers', 'CareerController@index')->name('careers');
Route::get('/careers/download/{id}', 'CareerController@download');
Route::get('/careers/{departmentslug}', 'CareerController@list')->name('careers.list');
Route::get('/careers/{departmentslug}/{slug}', 'CareerController@details')->name('careers.details');
Route::post('/careers/jobApply', 'CareerController@saveJobApplication');
Route::get('/privacy', 'HomeController@privacyPage')->name('privacy');
Route::get('/cookies', 'HomeController@cookiesPage')->name('cookies');
Route::get('/reports', 'ReportController@index')->name('reports');
Route::get('/industries/{slug}', 'ReportController@sectorReports');
Route::get('/report/{slug}', 'ReportController@details')->name('report.details');
Route::post('/reports/getReportFileType', 'ReportController@getReportFileType');
Route::post('/reports/getReportPrice', 'ReportController@getReportPrice');
Route::post('/reports/getReportData', 'ReportController@getReportData');
Route::get('/sample-request/{slug}', 'ReportController@sampleRequest')->name('report.sample-request');
Route::post('/requestSample','ReportController@saverequestSample')->name('request-a-sample');
Route::get('/speak-with-analyst/{slug}', 'ReportController@speakWithAnalyst');
Route::post('/saveSpeakWithAnalyst','ReportController@saveSpeakWithAnalyst')->name('speak-with-analyst');
Route::get('/subscribe-now/{slug}', 'ReportController@subscribeNow');
Route::post('/saveSubscribeNow','ReportController@saveSubscribeNow')->name('subscribe-now');
Route::get('/buy-now/{slug}', 'ReportController@buyNow')->name('report.buy');
Route::post('/saveReportOrder', 'ReportController@saveReportOrder');
Route::post('/redirectForPayment', 'ReportController@redirectForPayment')->name('redirectForPayment');
Route::get('/stripePaymentSuccess', 'ReportController@stripePaymentSuccess');
Route::get('/stripePaymentCancel', 'ReportController@stripePaymentCancel');
Route::post('/searchContent', 'HomeController@searchContent')->name('search');
Route::post('/search', 'HomeController@searchPage')->name('search-detail');
Route::get('/searchPageList', 'HomeController@searchPageList');
Route::get('/sitemap1.xml', 'SitemapXmlController@index');

/* Frontend User Auth Routes */
Route::get('reset-password/{token}', 'HomeController@index')->middleware('guest')->name('password.reset');

Route::namespace('Auth')->middleware(['guest'])->group(function () {
    Route::post('register', 'RegisteredUserController@store');
    Route::post('login', 'AuthenticatedSessionController@store');
    Route::post('forgot-password', 'PasswordResetLinkController@store')->name('password.email');
    //Route::get('reset-password/{token}', 'NewPasswordController@create')->name('password.reset');    
    Route::post('reset-password', 'NewPasswordController@store')->name('password.update');
});

/* Frontend Loggedin User Route */
Route::middleware(['auth'])->group(function () {
    Route::get('/my-reports', 'UserController@myReports')->name('my-reports');
    Route::get('/my-bookmarks', 'UserController@myBookmarks')->name('my-bookmarks');
    Route::get('/settings', 'UserController@settings')->name('settings');
    Route::get('/profile', 'UserController@myProfile')->name('profile');
    Route::post('/editProfile', 'UserController@editProfile')->name('edit-profile');
    Route::post('/userDeleteImage', 'UserController@deleteImage')->name('user.image');
    Route::get('/changePassword', 'UserController@changePassword')->name('change-password');
    Route::post('/savePassword', 'UserController@savePassword')->name('save-password');
    Route::post('/toggleBookmark', 'UserController@toggleBookmark')->name('toggle-bookmark');
    Route::post('/removeBookmark', 'UserController@removeBookmark')->name('remove-bookmark');
    Route::post('logout', 'Auth\AuthenticatedSessionController@destroy')->name('logout');
});

/* Admin Routes */
Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function(){
    Route::get('/',function () {
      return redirect(route('admin.dashboard'));
    })->name('home');

    /* Admin Auth Routes */
    Route::namespace('Auth')->middleware('guest:admin')->group(function(){
        //Login Routes
        Route::get('/login','AuthenticatedSessionController@create')->name('login');
        Route::post('/login','AuthenticatedSessionController@store');

        //Forgot Password Routes
        Route::get('/forgot-password', 'PasswordResetLinkController@create')->name('password.request');
        Route::post('/forgot-password', 'PasswordResetLinkController@store')->name('password.email');

        //Reset Password Routes
        Route::get('/reset-password/{token}', 'NewPasswordController@create')->name('password.reset');
        Route::post('/reset-password', 'NewPasswordController@store')->name('password.update');
    });

    Route::middleware('admin')->group(function () {

        Route::post('/upload', 'FileController@upload');
        
        Route::get('/dashboard','AdminController@dashboard')->name('dashboard');
        Route::post('/logout', 'Auth\AuthenticatedSessionController@destroy')->name('logout');

        Route::get('/profile', 'AdminController@profile')->name('profile');
        Route::post('/profile', 'AdminController@updateme')->name('updateme');
        Route::post('/deleteImage', 'AdminController@deleteAvatar')->name('avatar');
        Route::get('/changedPassword', 'AdminController@changPassword')->name('changepassword');
        Route::post('/changedPassword', 'AdminController@updatePassword');
        
        Route::get('/systemSettings', 'SettingsController@index')->name('systemsettings');
        Route::post('/systemSettings', 'SettingsController@update');
        
        Route::get('/reportpricing', 'ReportController@reportPricing')->name('reportpricing');
        Route::post('/reportpricing', 'ReportController@updateReportPricing');
        
        Route::get('/publishdate','ReportController@publishingDate')->name('publishdate');
        Route::post('/publishdate/import', 'ReportController@publishDateimportFiles')->name('uploadPublishDate');

        Route::get('/reportForecastSettings', 'ReportController@reportForecastSettings')->name('reportforecastsettings');
        Route::post('/reportForecastSettings', 'ReportController@updateReportForecastSettings');
        
        // Admin module Routes
        Route::get('/admin', 'AdminController@index')->name('admin.index');
       
        Route::post('/admin/ajax', 'AdminController@ajax')->name('admin.ajax');
        Route::get('/admin/add', 'AdminController@add')->name('admin.add');
        Route::post('/admin/store', 'AdminController@store')->name('admin.store');
        Route::get('/admin/edit/{id}', 'AdminController@edit')->name('admin.edit');
        Route::post('/admin/update', 'AdminController@update')->name('admin.update');
        Route::post('/admin/status', 'AdminController@status')->name('admin.status');
        Route::post('/admin/destroy', 'AdminController@destroy')->name('admin.destroy');

        // Roles module Routes
        Route::get('/roles', 'RoleController@index')->name('roles.index');       
        Route::post('/roles/ajax', 'RoleController@ajax')->name('roles.ajax');
        Route::get('/roles/add', 'RoleController@add')->name('roles.add');
        Route::post('/roles/store', 'RoleController@store')->name('roles.store');
        Route::get('/roles/edit/{id}', 'RoleController@edit')->name('roles.edit');
        Route::post('/roles/update', 'RoleController@update')->name('roles.update');
        Route::post('/roles/destroy', 'RoleController@destroy')->name('roles.destroy');

        // Service module Routes
        Route::get('/service', 'ServiceController@index')->name('service.index');
        Route::post('/service/ajax', 'ServiceController@ajax')->name('service.ajax');
        Route::get('/service/add', 'ServiceController@add')->name('service.add');
        Route::post('/service/store', 'ServiceController@store')->name('service.store');
        Route::get('/service/edit/{id}', 'ServiceController@edit')->name('service.edit');
        Route::post('/service/update', 'ServiceController@update')->name('service.update');
        Route::post('/service/status', 'ServiceController@status')->name('service.status');
        Route::post('/service/destroy', 'ServiceController@destroy')->name('service.destroy');
        Route::post('/service/deleteImage', 'ServiceController@deleteImage')->name('service.image');

        // Careers module Routes
        Route::get('/career', 'CareerController@index')->name('career.index');
        Route::post('/career/ajax', 'CareerController@ajax')->name('career.ajax');
        Route::get('/career/add', 'CareerController@add')->name('career.add');
        Route::post('/career/store', 'CareerController@store')->name('career.store');
        Route::get('/career/edit/{id}', 'CareerController@edit')->name('career.edit');
        Route::post('/career/update', 'CareerController@update')->name('career.update');
        Route::post('/career/status', 'CareerController@status')->name('career.status');
        Route::post('/career/destroy', 'CareerController@destroy')->name('career.destroy');

        // Users module Routes
        Route::get('/user', 'UserController@index')->name('user.index');
        Route::post('/user/ajax', 'UserController@ajax')->name('user.ajax');
        Route::get('/user/edit/{id}', 'UserController@edit')->name('user.edit');
        Route::post('/user/update', 'UserController@update')->name('user.update');
        Route::post('/user/status', 'UserController@status')->name('user.status');
        Route::post('/user/destroy', 'UserController@destroy')->name('user.destroy');
        Route::post('/user/deleteImage', 'UserController@deleteImage')->name('user.image');
        Route::get('/user/export','UserController@getUserData')->name('user.export');

        // Home page module Routes
        Route::get('/homepage', 'HomepageController@index')->name('homepage.index');
        Route::post('/homepage/update', 'HomepageController@update')->name('homepage.update');

        // Sectors module Routes
        Route::get('/sectors', 'SectorsController@index')->name('sectors.index');
        Route::post('/sectors/ajax', 'SectorsController@ajax')->name('sectors.ajax');
        Route::get('/sectors/add', 'SectorsController@add')->name('sectors.add');
        Route::post('/sectors/store', 'SectorsController@store')->name('sectors.store');
        Route::get('/sectors/edit/{id}', 'SectorsController@edit')->name('sectors.edit');
        Route::post('/sectors/update', 'SectorsController@update')->name('sectors.update');
        Route::post('/sectors/status', 'SectorsController@status')->name('sectors.status');
        Route::post('/sectors/destroy', 'SectorsController@destroy')->name('sectors.destroy');
        Route::post('/sectors/deleteImage', 'SectorsController@deleteImage')->name('sectors.image');

        // Case Study module Routes
        Route::get('/casestudy', 'CaseStudyController@index')->name('casestudy.index');
        Route::post('/casestudy/ajax', 'CaseStudyController@ajax')->name('casestudy.ajax');
        Route::get('/casestudy/add', 'CaseStudyController@add')->name('casestudy.add');
        Route::post('/casestudy/store', 'CaseStudyController@store')->name('casestudy.store');
        Route::get('/casestudy/edit/{id}', 'CaseStudyController@edit')->name('casestudy.edit');
        Route::post('/casestudy/update', 'CaseStudyController@update')->name('casestudy.update');
        Route::post('/casestudy/status', 'CaseStudyController@status')->name('casestudy.status');
        Route::post('/casestudy/destroy', 'CaseStudyController@destroy')->name('casestudy.destroy');
        Route::post('/casestudy/deleteImage', 'CaseStudyController@deleteImage')->name('casestudy.image');

        // Award module Routes
        Route::get('/award', 'AwardController@index')->name('award.index');
        Route::post('/award/ajax', 'AwardController@ajax')->name('award.ajax');
        Route::get('/award/add', 'AwardController@add')->name('award.add');
        Route::post('/award/store', 'AwardController@store')->name('award.store');
        Route::get('/award/edit/{id}', 'AwardController@edit')->name('award.edit');
        Route::post('/award/update', 'AwardController@update')->name('award.update');
        Route::post('/award/status', 'AwardController@status')->name('award.status');
        Route::post('/award/destroy', 'AwardController@destroy')->name('award.destroy');
        Route::post('/award/deleteImage', 'AwardController@deleteImage')->name('award.image');

        // Insights module Routes
        Route::get('/insight', 'InsightController@index')->name('insight.index');
        Route::post('/insight/ajax', 'InsightController@ajax')->name('insight.ajax');
        Route::get('/insight/add', 'InsightController@add')->name('insight.add');
        Route::post('/insight/store', 'InsightController@store')->name('insight.store');
        Route::get('/insight/edit/{id}', 'InsightController@edit')->name('insight.edit');
        Route::post('/insight/update', 'InsightController@update')->name('insight.update');
        Route::post('/insight/status', 'InsightController@status')->name('insight.status');
        Route::post('/insight/destroy', 'InsightController@destroy')->name('insight.destroy');
        Route::post('/insight/checkImage', 'InsightController@checkImage')->name('insight.checkImage');
        Route::post('/insight/deleteImage', 'InsightController@deleteImage')->name('insight.image');
        Route::post('/insight/deleteWriterImage', 'InsightController@deleteWriterImage')->name('insight.writerimage');

        // Client Feedback module Routes
        Route::get('/client-feedback', 'ClientFeedbackController@index')->name('client-feedback.index');
        Route::post('/client-feedback/ajax', 'ClientFeedbackController@ajax')->name('client-feedback.ajax');
        Route::get('/client-feedback/add', 'ClientFeedbackController@add')->name('client-feedback.add');
        Route::post('/client-feedback/store', 'ClientFeedbackController@store')->name('client-feedback.store');
        Route::get('/client-feedback/edit/{id}', 'ClientFeedbackController@edit')->name('client-feedback.edit');
        Route::post('/client-feedback/update', 'ClientFeedbackController@update')->name('client-feedback.update');
        Route::post('/client-feedback/status', 'ClientFeedbackController@status')->name('client-feedback.status');
        Route::post('/client-feedback/destroy', 'ClientFeedbackController@destroy')->name('client-feedback.destroy');
        Route::post('/client-feedback/deleteImage', 'ClientFeedbackController@deleteImage')->name('client-feedback.image');

        // Sector module Routes
        Route::get('/sector', 'SectorController@index')->name('sector.index');
        Route::post('/sector/ajax', 'SectorController@ajax')->name('sector.ajax');
        Route::get('/sector/add', 'SectorController@add')->name('sector.add');
        Route::post('/sector/store', 'SectorController@store')->name('sector.store');
        Route::get('/sector/edit/{id}', 'SectorController@edit')->name('sector.edit');
        Route::post('/sector/update', 'SectorController@update')->name('sector.update');
        Route::post('/sector/status', 'SectorController@status')->name('sector.status');
        Route::post('/sector/destroy', 'SectorController@destroy')->name('sector.destroy');

        // Industry Group module Routes
        Route::get('/industry-group', 'IndustryGroupController@index')->name('industry-group.index');
        Route::post('/industry-group/ajax', 'IndustryGroupController@ajax')->name('industry-group.ajax');
        Route::get('/industry-group/add', 'IndustryGroupController@add')->name('industry-group.add');
        Route::post('/industry-group/store', 'IndustryGroupController@store')->name('industry-group.store');
        Route::get('/industry-group/edit/{id}', 'IndustryGroupController@edit')->name('industry-group.edit');
        Route::post('/industry-group/update', 'IndustryGroupController@update')->name('industry-group.update');
        Route::post('/industry-group/status', 'IndustryGroupController@status')->name('industry-group.status');
        Route::post('/industry-group/destroy', 'IndustryGroupController@destroy')->name('industry-group.destroy');

        // Industry module Routes
        Route::get('/industry', 'IndustryController@index')->name('industry.index');
        Route::post('/industry/ajax', 'IndustryController@ajax')->name('industry.ajax');
        Route::get('/industry/add', 'IndustryController@add')->name('industry.add');
        Route::post('/industry/store', 'IndustryController@store')->name('industry.store');
        Route::get('/industry/edit/{id}', 'IndustryController@edit')->name('industry.edit');
        Route::post('/industry/update', 'IndustryController@update')->name('industry.update');
        Route::post('/industry/status', 'IndustryController@status')->name('industry.status');
        Route::post('/industry/destroy', 'IndustryController@destroy')->name('industry.destroy');

        // Sub Industry module Routes
        Route::get('/sub-industry', 'SubIndustryController@index')->name('sub-industry.index');
        Route::post('/sub-industry/ajax', 'SubIndustryController@ajax')->name('sub-industry.ajax');
        Route::get('/sub-industry/add', 'SubIndustryController@add')->name('sub-industry.add');
        Route::post('/sub-industry/store', 'SubIndustryController@store')->name('sub-industry.store');
        Route::get('/sub-industry/edit/{id}', 'SubIndustryController@edit')->name('sub-industry.edit');
        Route::post('/sub-industry/update', 'SubIndustryController@update')->name('sub-industry.update');
        Route::post('/sub-industry/status', 'SubIndustryController@status')->name('sub-industry.status');
        Route::post('/sub-industry/destroy', 'SubIndustryController@destroy')->name('sub-industry.destroy');

        // Appoinments module Routes
        Route::get('/appointment', 'AppointmentController@index')->name('appointment.index');
        Route::post('/appointment/ajax', 'AppointmentController@ajax')->name('appointment.ajax');        
        Route::get('/appointment/view/{id}', 'AppointmentController@view')->name('appointment.view');
        Route::get('/appointment/export','AppointmentController@getAppointmentData')->name('appointment.export');

        // Contact Us module Routes
        Route::get('/contactus', 'ContactUsController@index')->name('contactus.index');
        Route::post('/contactus/ajax', 'ContactUsController@ajax')->name('contactus.ajax');        
        Route::get('/contactus/view/{id}', 'ContactUsController@view')->name('contactus.view');
        Route::get('/contactus/export','ContactUsController@getContactUsData')->name('contactus.export');

        // Our Team module Routes
        Route::get('/our-team', 'OurTeamController@index')->name('our-team.index');
        Route::post('/our-team/ajax', 'OurTeamController@ajax')->name('our-team.ajax');
        Route::get('/our-team/add', 'OurTeamController@add')->name('our-team.add');
        Route::post('/our-team/store', 'OurTeamController@store')->name('our-team.store');
        Route::get('/our-team/edit/{id}', 'OurTeamController@edit')->name('our-team.edit');
        Route::post('/our-team/update', 'OurTeamController@update')->name('our-team.update');
        Route::post('/our-team/status', 'OurTeamController@status')->name('our-team.status');
        Route::post('/our-team/destroy', 'OurTeamController@destroy')->name('our-team.destroy');
        Route::post('/our-team/deleteImage', 'OurTeamController@deleteImage')->name('our-team.image');

        // Job Applications module Routes
        Route::get('/job-application', 'JobApplicationController@index')->name('job-application.index');
        Route::post('/job-application/ajax', 'JobApplicationController@ajax')->name('job-application.ajax');        
        Route::get('/job-application/view/{id}', 'JobApplicationController@view')->name('job-application.view');
        Route::get('/job-application/download/{id}', 'JobApplicationController@download')->name('job-application.download');
        Route::get('/job-application/export','JobApplicationController@getJobApplicationData')->name('job-application.export');
        Route::post('/job-application/destroy', 'JobApplicationController@destroy')->name('job-application.destroy');

        // 404-Page not found module Routes
        Route::get('/404-inquiry', 'PageNotFoundInquiryController@index')->name('404-inquiry.index');
        Route::post('/404-inquiry/ajax', 'PageNotFoundInquiryController@ajax')->name('404-inquiry.ajax');        
        Route::get('/404-inquiry/view/{id}', 'PageNotFoundInquiryController@view')->name('404-inquiry.view');
        Route::get('/404-inquiry/export','PageNotFoundInquiryController@getPageNotFoundData')->name('404-inquiry.export');
        Route::post('/404-inquiry/destroy', 'PageNotFoundInquiryController@destroy')->name('404-inquiry.destroy');

        //reports module
        Route::get('/report', 'ReportController@index')->name('report.index');
        Route::post('/report/ajax', 'ReportController@ajax')->name('report.ajax');
        Route::post('/report/import', 'ReportController@importFiles')->name('report.import');
        Route::get('/report/edit/{id}', 'ReportController@edit')->name('report.edit');
        Route::get('/report/create', 'ReportController@create')->name('report.create');
        Route::post('/report/store', 'ReportController@store')->name('report.store');
        Route::post('/report/update', 'ReportController@update')->name('report.update');
        Route::post('/report/status', 'ReportController@status')->name('report.status');
        Route::post('/report/destroy', 'ReportController@destroy')->name('report.destroy');
        Route::post('/report/deleteSegment', 'ReportController@deleteSegment')->name('report.deleteSegment');
        Route::post('/report/deleteFaq', 'ReportController@deleteFaq')->name('report.deleteFaq');
        Route::post('/report/graphimport', 'ReportController@graphimportFiles')->name('report.graphimport');
        Route::get('/report/migrateReports', 'ReportController@migrateReports')->name('report.migrateReports');
        Route::get('/report/migrateReportData', 'ReportController@migrateReportData')->name('report.migrateReportData');
        Route::get('/report/migrateUpcomingReports', 'ReportController@migrateUpcomingReports')->name('report.migrateUpcomingReports');
        Route::get('/report/schemaGeneration', 'ReportController@schemaGeneration')->name('report.schemaGeneration');
        Route::post('/report/getIndustryData', 'ReportController@getIndustryData')->name('report.getIndustryData');

        //free sample request for the reports module
        Route::get('/free-sample-request', 'SampleRequestController@index')->name('free-sample-request.index');
        Route::post('/free-sample-request/ajax', 'SampleRequestController@ajax')->name('free-sample-request.ajax');
        Route::get('/free-sample-request/view/{id}', 'SampleRequestController@view')->name('free-sample-request.view');
        Route::get('/free-sample-request/export','SampleRequestController@getSampleRequestData')->name('free-sample-request.export');
        Route::post('/free-sample-request/destroy', 'SampleRequestController@destroy')->name('free-sample-request.destroy');

        //report inquiry for the reports module
        Route::get('/report-inquiry', 'ReportInquiryController@index')->name('report-inquiry.index');
        Route::post('/report-inquiry/ajax', 'ReportInquiryController@ajax')->name('report-inquiry.ajax');
        Route::get('/report-inquiry/view/{id}', 'ReportInquiryController@view')->name('report-inquiry.view');
        Route::post('/report-inquiry/export','ReportInquiryController@getReportInquiryData')->name('report-inquiry.export');
        Route::post('/report-inquiry/destroy', 'ReportInquiryController@destroy')->name('report-inquiry.destroy');

        //report subscription for the reports module
        Route::get('/report-subscription', 'ReportSubscriptionController@index')->name('report-subscription.index');
        Route::post('/report-subscription/ajax', 'ReportSubscriptionController@ajax')->name('report-subscription.ajax');
        Route::get('/report-subscription/view/{id}', 'ReportSubscriptionController@view')->name('report-subscription.view');
        Route::get('/report-subscription/export','ReportSubscriptionController@getReportInquiryData')->name('report-subscription.export');
        Route::post('/report-subscription/destroy', 'ReportSubscriptionController@destroy')->name('report-subscription.destroy');

        //buy report or orders for the reports module
        Route::get('/report-order', 'ReportOrdersController@index')->name('report-order.index');
        Route::post('/report-order/ajax', 'ReportOrdersController@ajax')->name('report-order.ajax');
        Route::get('/report-order/view/{id}', 'ReportOrdersController@view')->name('report-order.view');
        Route::get('/report-order/export','ReportOrdersController@getReportOrdersData')->name('report-order.export');
        Route::post('/report-order/destroy', 'ReportOrdersController@destroy')->name('report-order.destroy');

        //gallery module
        Route::get('/gallery', 'GalleryController@index')->name('gallery.index');
        Route::post('/gallery/ajax', 'GalleryController@ajax')->name('gallery.ajax');
        Route::get('/gallery/add', 'GalleryController@add')->name('gallery.add');
        Route::post('/gallery/store', 'GalleryController@store')->name('gallery.store');
        Route::get('/gallery/edit/{id}', 'GalleryController@edit')->name('gallery.edit');
        Route::post('/gallery/update', 'GalleryController@update')->name('gallery.update');
        Route::get('/gallery/view/{id}', 'GalleryController@view')->name('gallery.view');
        Route::post('/gallery/deleteImage', 'GalleryController@deleteImage')->name('gallery.image');

        // pages module Routes
        Route::get('/pages', 'PageController@index')->name('pages.index');
        Route::post('/pages/ajax', 'PageController@ajax')->name('pages.ajax');
        Route::get('/pages/edit/{id}', 'PageController@edit')->name('pages.edit');
        Route::post('/pages/update', 'PageController@update')->name('pages.update');
        
        //email-restriction Routes
        Route::get('/email-restriction', 'EmailRestrictionController@index')->name('email-restriction.index');
        Route::post('/email-restriction/ajax', 'EmailRestrictionController@ajax')->name('email-restriction.ajax');
        Route::get('/email-restriction/add', 'EmailRestrictionController@add')->name('email-restriction.add');
        Route::post('/email-restriction/store', 'EmailRestrictionController@store')->name('email-restriction.store');
        Route::get('/email-restriction/edit/{id}', 'EmailRestrictionController@edit')->name('email-restriction.edit');
        Route::post('/email-restriction/update', 'EmailRestrictionController@update')->name('email-restriction.update');
        Route::post('/email-restriction/destroy', 'EmailRestrictionController@destroy')->name('email-restriction.destroy');

        // Report export module
        Route::get('/report-export', 'ReportExportController@index')->name('report-export.index');
        Route::post('/report-export/store', 'ReportExportController@store')->name('report-export.store');
        Route::get('/report-export/download/{uuid}', 'ReportExportController@download')->name('report-export.download');
    });
    
});

/* Clear catch */
Route::get('/clear', function () {
    $stream = fopen("php://output", "w");
    Artisan::call("config:clear", array(), new Symfony\Component\Console\Output\StreamOutput($stream));
    Artisan::call("cache:clear", array(), new Symfony\Component\Console\Output\StreamOutput($stream));
    Artisan::call("config:cache", array(), new Symfony\Component\Console\Output\StreamOutput($stream));
    Artisan::call("route:clear", array(), new Symfony\Component\Console\Output\StreamOutput($stream));
    Artisan::call("view:clear", array(), new Symfony\Component\Console\Output\StreamOutput($stream));
    Artisan::call("clear-compiled", array(), new Symfony\Component\Console\Output\StreamOutput($stream));
    return Artisan::output();
});

Route::get('404', 'HomeController@pagenotfound');
//Route::post('/savePageNotFound', 'HomeController@savePageNotFound');

Route::fallback(function () {
    return redirect('404');
});