<script setup lang="ts">
import { computed } from 'vue';
import vueFilePond from 'vue-filepond';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginMediaPreview from 'filepond-plugin-media-preview';

const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize,
    FilePondPluginImagePreview,
    FilePondPluginMediaPreview,
);

const props = withDefaults(
    defineProps<{
        modelValue: File[];
        maxFiles?: number;
        acceptedFileTypes?: string[];
        maxFileSize?: string;
        labelIdle?: string;
        helpText?: string;
        disabled?: boolean;
    }>(),
    {
        maxFiles: 5,
        acceptedFileTypes: () => ['image/*', 'video/*', 'application/pdf'],
        maxFileSize: '500MB',
        labelIdle: 'Arrastra y suelta archivos o <span class="filepond--label-action">Selecciona</span>',
        helpText: 'Imágenes, video o PDF. Máx. 500MB por archivo.',
        disabled: false,
    },
);

const emit = defineEmits<{
    (e: 'update:modelValue', value: File[]): void;
}>();

const files = computed(() => props.modelValue ?? []);

function handleUpdateFiles(fileItems: Array<{ file?: File }>): void {
    emit(
        'update:modelValue',
        fileItems.map((item) => item.file).filter((file): file is File => Boolean(file)),
    );
}
</script>

<template>
    <div class="space-y-2">
        <FilePond
            name="attachments"
            :files="files"
            :allow-multiple="maxFiles > 1"
            :max-files="maxFiles"
            :accepted-file-types="acceptedFileTypes"
            :max-file-size="maxFileSize"
            :disabled="disabled"
            :credits="false"
            :label-idle="labelIdle"
            :instant-upload="false"
            @updatefiles="handleUpdateFiles"
        />

        <p v-if="helpText" class="text-xs text-slate-500 dark:text-slate-400">
            {{ helpText }}
        </p>
    </div>
</template>

