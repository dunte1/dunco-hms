# Quick Script to Update Remaining Sidebar Placeholders
# This document shows all the replacements needed

# Finance Section Placeholders
Payment Receipts:
  FROM: <a href="#" class="flex items-center px-4 py-1.5 text-sm rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900">
          <i class="fa fa-receipt mr-2 w-4"></i> Payment Receipts
  TO: <a href="{{ route('hms.billing.receipts') }}" class="flex items-center px-4 py-1.5 text-sm rounded-lg {{ request()->routeIs('hms.billing.receipts') ? 'bg-blue-200 dark:bg-blue-900 text-blue-800 dark:text-blue-200' : 'hover:bg-blue-100 dark:hover:bg-blue-900' }}">
        <i class="fa fa-receipt mr-2 w-4"></i> Payment Receipts

Payment Reports:
  FROM: <a href="#" class="flex items-center px-4 py-1.5 text-sm rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900">
          <i class="fa fa-chart-line mr-2 w-4"></i> Payment Reports
  TO: <a href="{{ route('hms.billing.payment-reports') }}" class="flex items-center px-4 py-1.5 text-sm rounded-lg {{ request()->routeIs('hms.billing.payment-reports') ? 'bg-blue-200 dark:bg-blue-900 text-blue-800 dark:text-blue-200' : 'hover:bg-blue-100 dark:hover:bg-blue-900' }}">
        <i class="fa fa-chart-line mr-2 w-4"></i> Payment Reports

# Continue for all other placeholders following the same pattern...
# All routes are already created in routes/web.php
# Just need to update href="#" to href="{{ route('route.name') }}" with proper highlighting

