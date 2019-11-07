<label>App Version</label>
<select required name="app_version" class="select2 form-control">
    <option value="all">All</option>
@foreach($appVersions as $appVersion)
        <option value={{$appVersion->app_version}}>{{$appVersion->app_version}}</option>
    @endforeach
        <option value="others">Others</option>
</select>