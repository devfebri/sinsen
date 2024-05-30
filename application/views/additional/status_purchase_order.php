<td class=" text-center">
    <div class="col-md-8" style="padding:0;">
        <ul class="progress-indicator" style="margin-bottom: 0px;">
            <li class="<?= in_array($status, [
                        'Draft',
                        'Approved',
                        'Submitted',
                        'Processed by MD',
                        'Processed',
                        'Closed'
                        ]) ? 'active' : '' ?>"><span class="bubble"></span>
            Draft
            </li>
            <li class="<?= in_array($status, [
                        'Approved',
                        'Submitted',
                        'Processed by MD',
                        'Processed',
                        'Closed'
                        ]) ? 'active' : '' ?>"><span class="bubble"></span>Approved</li>
            <li class="<?= in_array($status, [
                        'Submitted',
                        'Processed by MD',
                        'Processed',
                        'Closed'
                        ]) ? 'active' : '' ?>"><span class="bubble"></span>Submitted</li>
            <li class="<?= in_array($status, [
                        'Processed by MD',
                        'Processed',
                        'Closed'
                        ]) ? 'active' : '' ?>"><span class="bubble"></span>Processed</li>
            <li class="<?= in_array($status, [
                        'Closed'
                        ]) ? 'active' : '' ?>"><span class="bubble"></span>Closed</li>
        </ul>
    </div>
    <div class="col-md-2 text-center" style="padding:0;">
        <ul class="progress-indicator_one" style="margin-bottom: 0px;">
            <li class="<?= $status == 'Canceled' ? 'danger' : '' ?>"><span class="bubble"></span>Canceled</li>
        </ul>
    </div>
    <div class="col-md-2 text-center"style="padding:0;">
        <ul class="progress-indicator_one" style="margin-bottom: 0px;">
            <li class="<?= $status == 'Rejected' ? 'danger' : '' ?>"><span class="bubble"></span>Rejected</li>
        </ul>
    </div>
    <div class="col-md-2 text-center"style="padding:0;">
        <ul class="progress-indicator_one" style="margin-bottom: 0px;">
            <li class="<?= $status == 'Reject & Revisi by MD' || $status == 'Revisi Dealer' ? 'warning' : '' ?>"><span class="bubble"></span>Revisi</li>
        </ul>
    </div>
</td>