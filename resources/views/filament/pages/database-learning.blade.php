<x-filament-panels::page>
    <div class="space-y-8">
        <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-2">
                <h2 class="text-lg font-semibold text-gray-900">Relationship Patterns</h2>
                <p class="text-sm text-gray-500">
                    Model richer ATS behaviors by choosing the right relationship type for each workflow.
                </p>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                @foreach ($relationshipTopics as $topic)
                    <article class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                        <h3 class="text-sm font-semibold text-gray-900">{{ $topic['title'] }}</h3>
                        <p class="mt-2 text-xs text-gray-600">{{ $topic['summary'] }}</p>
                        <div class="mt-3 text-xs text-gray-700">
                            <p class="uppercase tracking-wide text-gray-500">ATS Example</p>
                            <p>{{ $topic['atsExample'] }}</p>
                        </div>
                        <div class="mt-3 text-xs text-gray-700">
                            <p class="uppercase tracking-wide text-gray-500">Modeling Hint</p>
                            <code class="text-gray-700">{{ $topic['implementation'] }}</code>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-2">
                <h2 class="text-lg font-semibold text-gray-900">CTE Query Patterns</h2>
                <p class="text-sm text-gray-500">
                    Copy these snippets into Postgres to explore funnel counts, velocity, and recursive trees.
                </p>
            </div>

            <div class="mt-6 space-y-6">
                @foreach ($cteExamples as $example)
                    <article class="space-y-2">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">{{ $example['title'] }}</h3>
                            <p class="text-xs text-gray-600">{{ $example['summary'] }}</p>
                        </div>
                        <pre class="overflow-x-auto rounded-lg border border-gray-200 bg-gray-900 p-4 text-xs text-gray-100"><code>{{ $example['sql'] }}</code></pre>
                    </article>
                @endforeach
            </div>
        </section>
    </div>
</x-filament-panels::page>
