@props(['class' => ''])

<div class="overflow-x-auto">
  <table {{ $attributes->merge(['class' => "min-w-full divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900 rounded-lg shadow-md overflow-hidden " . $class]) }}>
      <thead class="bg-gray-50 dark:bg-gray-800">
          {{ $thead ?? '' }}
      </thead>
      <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          {{ $tbody ?? '' }}
      </tbody>
  </table>
</div>
