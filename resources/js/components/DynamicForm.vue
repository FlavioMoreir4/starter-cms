<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

interface SchemaProperty {
    type?: string;
    title?: string;
    description?: string;
    format?: string;
    'x-filament'?: {
        type?: string;
        options?: Record<string, string>;
        placeholder?: string;
    };
}

interface Schema {
    type?: string;
    properties?: Record<string, SchemaProperty>;
    required?: string[];
}

const props = withDefaults(defineProps<{
    schema: Schema;
    action: string;
    successMessage?: string;
}>(), {
    successMessage: '',
});

const page = usePage();
const flashSuccess = computed(() => (page.props.flash as Record<string, string> | undefined)?.success);
const showSuccess = ref(false);

const initialPayload = computed(() => {
    const data: Record<string, string | number | boolean> = {};

    for (const [key, field] of Object.entries(props.schema.properties ?? {})) {
        const fieldType = field['x-filament']?.type ?? inferType(field);
        data[key] = fieldType === 'checkbox' ? false : '';
    }

    return data;
});

const form = useForm({
    payload: initialPayload.value,
});

const submit = () => {
    form.post(props.action, {
        preserveScroll: true,
        onSuccess: () => {
            showSuccess.value = true;
            form.reset('payload');
            form.defaults('payload', initialPayload.value);
        },
    });
};

const inferType = (field: SchemaProperty): string => {
    switch (field.type) {
        case 'number':
        case 'integer':
            return 'number';
        case 'boolean':
            return 'checkbox';
        default:
            return 'text';
    }
};

const getFieldType = (field: SchemaProperty): string => {
    return field['x-filament']?.type ?? inferType(field);
};

const getPlaceholder = (field: SchemaProperty): string => {
    return field['x-filament']?.placeholder ?? '';
};

const getOptions = (field: SchemaProperty): Record<string, string> => {
    return field['x-filament']?.options ?? {};
};

const isRequired = (name: string): boolean => {
    return (props.schema.required ?? []).includes(name);
};

const getFieldError = (name: string): string => {
    const errors = form.errors as Record<string, string>;

    return errors[`payload.${name}`] ?? errors[name] ?? '';
};
</script>

<template>
    <div v-if="showSuccess && (flashSuccess || successMessage)"
        class="rounded-md border border-green-200 bg-green-50 p-4 text-sm text-green-800 dark:border-green-800 dark:bg-green-950 dark:text-green-200">
        {{ flashSuccess || successMessage }}
    </div>

    <form v-if="!showSuccess || (!flashSuccess && !successMessage)" class="space-y-6" @submit.prevent="submit">
        <div v-for="(field, name) in schema.properties" :key="name" class="space-y-2">
            <!-- Text / Number / Date -->
            <template v-if="['text', 'number', 'date'].includes(getFieldType(field))">
                <Label :for="String(name)">
                    {{ field.title || name }}
                    <span v-if="isRequired(String(name))" class="text-destructive ml-1">*</span>
                </Label>
                <Input :id="String(name)" v-model="form.payload[name]"
                    :type="getFieldType(field) === 'number' ? 'number' : getFieldType(field) === 'date' ? 'date' : 'text'"
                    :placeholder="getPlaceholder(field)" :aria-invalid="!!getFieldError(String(name))" />
            </template>

            <!-- Textarea -->
            <template v-else-if="getFieldType(field) === 'textarea'">
                <Label :for="String(name)">
                    {{ field.title || name }}
                    <span v-if="isRequired(String(name))" class="text-destructive ml-1">*</span>
                </Label>
                <textarea :id="String(name)" v-model="form.payload[name]" :placeholder="getPlaceholder(field)"
                    :aria-invalid="!!getFieldError(String(name))" rows="4"
                    class="file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input flex w-full min-w-0 rounded-md border bg-transparent px-3 py-2 text-base shadow-xs transition-[color,box-shadow] outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive" />
            </template>

            <!-- Select -->
            <template v-else-if="getFieldType(field) === 'select'">
                <Label>
                    {{ field.title || name }}
                    <span v-if="isRequired(String(name))" class="text-destructive ml-1">*</span>
                </Label>
                <Select :model-value="(form.payload[name] as string)"
                    @update:model-value="(value: string) => form.payload[name] = value">
                    <SelectTrigger :class="getFieldError(String(name)) ? 'border-destructive' : ''">
                        <SelectValue :placeholder="getPlaceholder(field) || 'Selecione...'" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="(label, value) in getOptions(field)" :key="String(value)"
                            :value="String(value)">
                            {{ label }}
                        </SelectItem>
                    </SelectContent>
                </Select>
            </template>

            <!-- Checkbox -->
            <template v-else-if="getFieldType(field) === 'checkbox'">
                <div class="flex items-center gap-2">
                    <Checkbox :id="String(name)" :checked="form.payload[name] as boolean"
                        @update:checked="(checked: boolean) => form.payload[name] = checked" />
                    <Label :for="String(name)" class="font-normal">
                        {{ field.title || name }}
                    </Label>
                </div>
            </template>

            <!-- Radio -->
            <template v-else-if="getFieldType(field) === 'radio'">
                <Label>
                    {{ field.title || name }}
                    <span v-if="isRequired(String(name))" class="text-destructive ml-1">*</span>
                </Label>
                <div class="flex flex-col gap-2">
                    <div v-for="(label, value) in getOptions(field)" :key="String(value)"
                        class="flex items-center gap-2">
                        <input :id="`${name}-${value}`" type="radio" :name="String(name)" :value="String(value)"
                            :checked="form.payload[name] === String(value)"
                            class="text-primary focus-visible:ring-ring/50 h-4 w-4 border-input"
                            @change="form.payload[name] = String(value)" />
                        <Label :for="`${name}-${value}`" class="font-normal">
                            {{ label }}
                        </Label>
                    </div>
                </div>
            </template>

            <!-- Error message -->
            <p v-if="getFieldError(String(name))" class="text-sm text-red-600 dark:text-red-500">
                {{ getFieldError(String(name)) }}
            </p>

            <!-- Description / Help text -->
            <p v-if="field.description && getFieldType(field) !== 'checkbox'" class="text-sm text-muted-foreground">
                {{ field.description }}
            </p>
        </div>

        <Button type="submit" :disabled="form.processing">
            <span v-if="form.processing"
                class="mr-2 inline-block h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent" />
            {{ form.processing ? 'Enviando...' : 'Enviar' }}
        </Button>
    </form>
</template>
