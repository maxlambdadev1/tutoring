<div>
    @php
    $title = "Add a lead";
    $breadcrumbs = ["Leads", "Add a lead"];
    @endphp
    <x-custom-header :title="$title" :breadcrumbs="$breadcrumbs" />

    <livewire:admin.components.create-lead thirdparty_org_id=""/>
</div>