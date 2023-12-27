<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('home') }}</loc>
        <lastmod>{{ gmdate(DateTime::W3C, strtotime(now())) }}</lastmod>
        <changefreq>weekly</changefreq>
    </url>
    <url>
        <loc>{{ route('about-us') }}</loc>
        <lastmod>{{ gmdate(DateTime::W3C, strtotime(now())) }}</lastmod>
        <changefreq>weekly</changefreq>
    </url>
    <url>
        <loc>{{ route('careers') }}</loc>
        <lastmod>{{ gmdate(DateTime::W3C, strtotime(now())) }}</lastmod>
        <changefreq>weekly</changefreq>
    </url>
    <url>
        <loc>{{ route('case-studies') }}</loc>
        <lastmod>{{ gmdate(DateTime::W3C, strtotime(now())) }}</lastmod>
        <changefreq>weekly</changefreq>
    </url>
    <url>
        <loc>{{ route('contact-us') }}</loc>
        <lastmod>{{ gmdate(DateTime::W3C, strtotime(now())) }}</lastmod>
        <changefreq>weekly</changefreq>
    </url>
    <url>
        <loc>{{ route('cookies') }}</loc>
        <lastmod>{{ gmdate(DateTime::W3C, strtotime(now())) }}</lastmod>
        <changefreq>weekly</changefreq>
    </url>
    <url>
        <loc>{{ route('insights') }}</loc>
        <lastmod>{{ gmdate(DateTime::W3C, strtotime(now())) }}</lastmod>
        <changefreq>weekly</changefreq>
    </url>
    <url>
        <loc>{{ route('privacy') }}</loc>
        <lastmod>{{ gmdate(DateTime::W3C, strtotime(now())) }}</lastmod>
        <changefreq>weekly</changefreq>
    </url>
    <url>
        <loc>{{ route('services') }}</loc>
        <lastmod>{{ gmdate(DateTime::W3C, strtotime(now())) }}</lastmod>
        <changefreq>weekly</changefreq>
    </url>
    @foreach ($services as $service)
        <url>
            <loc>{{ url('/') }}/services/{{ $service->slug }}</loc>
            <lastmod>{{ $service->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
        </url>
    @endforeach
    @foreach ($reports as $report)
        <url>
            <loc>{{ url('/') }}/report/{{ $report->slug }}</loc>
            <lastmod>{{ $report->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
        </url>
    @endforeach
    @foreach ($sectorData as $sector)
        <url>
            <loc>{{ url('/') }}/industries/{{ $sector->slug }}</loc>
            <lastmod>{{ $sector->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
        </url>
    @endforeach
    @foreach ($industry_group_data as $industry_group)
        <url>
            <loc>{{ url('/') }}/industries/{{ $industry_group->slug }}</loc>
            <lastmod>{{ $industry_group->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
        </url>
    @endforeach
    @foreach ($industry_data as $industry)
        <url>
            <loc>{{ url('/') }}/industries/{{ $industry->slug }}</loc>
            @if($industry->updated_at!='')
            <lastmod>{{ $industry->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            @else
            <lastmod>{{ gmdate(DateTime::W3C, strtotime(now())) }}</lastmod>
            @endif
            <changefreq>weekly</changefreq>
        </url>
    @endforeach
    @foreach ($sub_industry_data as $sub_industry)
        <url>
            <loc>{{ url('/') }}/industries/{{ $sub_industry->slug }}</loc>
            @if($sub_industry->updated_at!='')
            <lastmod>{{ $sub_industry->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            @else
            <lastmod>{{ gmdate(DateTime::W3C, strtotime(now())) }}</lastmod>
            @endif
            <changefreq>weekly</changefreq>
        </url>
    @endforeach
    @foreach ($insights as $insight)
        <url>
            <loc>{{ url('/') }}/insights/{{ $insight->slug }}</loc>
            <lastmod>{{ $insight->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
        </url>
    @endforeach
    @foreach ($case_studies as $case_study)
        <url>
            <loc>{{ url('/') }}/case-studies/{{ $case_study->slug }}</loc>
            <lastmod>{{ $case_study->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
        </url>
    @endforeach
</urlset>